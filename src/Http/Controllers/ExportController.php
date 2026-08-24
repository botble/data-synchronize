<?php

namespace Botble\DataSynchronize\Http\Controllers;

use BackedEnum;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Requests\ExportRequest;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

abstract class ExportController extends BaseController
{
    /**
     * Row count above which a CSV export switches to streaming.
     */
    protected const STREAMING_THRESHOLD = 10000;

    abstract protected function getExporter(): Exporter;

    protected function allowsSelectColumns(): bool
    {
        return true;
    }

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('core/base::layouts.tools'))
            ->add(trans('packages/data-synchronize::data-synchronize.tools.export_import_data'), route('tools.data-synchronize'));
    }

    public function index()
    {
        $this->pageTitle($this->getExporter()->getHeading());

        return $this->getExporter()->render();
    }

    public function store(ExportRequest $request)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('core/base::system.disabled_in_demo_mode'));
        }

        try {
            $exporter = $this->getExporter();

            $totalItems = $this->resolveTotalItems($exporter);

            if ($totalItems > static::STREAMING_THRESHOLD && $request->input('format') === 'xlsx') {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage(trans('packages/data-synchronize::data-synchronize.export.excel_not_supported_for_large_exports', ['count' => number_format($totalItems)]));
            }

            $exporter->format($request->input('format'));

            if ($this->allowsSelectColumns()) {
                $exporter->acceptedColumns($request->input('columns'));
            }

            // Configure memory optimization
            if ($request->boolean('optimize_memory', true)) {
                $exporter->setOptimizeMemory(true);
            }

            // Configure chunk size if provided
            if ($chunkSize = $request->integer('chunk_size')) {
                if (method_exists($exporter, 'setChunkSize')) {
                    $exporter->setChunkSize($chunkSize);
                }
            }

            // Configure chunked export
            if ($request->has('use_chunked_export') && method_exists($exporter, 'useChunkedExport')) {
                $exporter->useChunkedExport($request->boolean('use_chunked_export'));
            }

            // Configure include variations (for product exports)
            if ($request->has('include_variations') && method_exists($exporter, 'setIncludeVariations')) {
                $exporter->setIncludeVariations($request->boolean('include_variations'));
            }

            // Configure streaming mode for large exports
            if (method_exists($exporter, 'enableStreamingMode')) {
                $enableStreaming = $request->boolean('use_streaming', false);

                if (! $enableStreaming) {
                    $enableStreaming = $this->resolveTotalItems($exporter) > static::STREAMING_THRESHOLD;
                }

                if ($enableStreaming) {
                    $exporter->enableStreamingMode(true);
                }
            }

            if ($request->boolean('stream', false)) {
                return $this->streamExport($exporter, $request);
            }

            if (
                method_exists($exporter, 'isStreamingMode')
                && $exporter->isStreamingMode()
                && method_exists($exporter, 'streamingGenerator')
                && $request->input('format') === 'csv'
            ) {
                return $this->streamingExport($exporter, $request);
            }

            return $exporter->export();
        } catch (Throwable $e) {
            BaseHelper::logError($e);

            return $this
                ->httpResponse()
                ->setError()
                ->setCode(400)
                ->setMessage($e->getMessage());
        }
    }

    /**
     * Largest row count the exporter reports through its counters.
     *
     * This used to look for the counter whose label contained "total", but that label
     * is translated - the check only ever worked on an English admin. The totals
     * counter is a sum of the others, so the largest value is the one we want.
     */
    protected function resolveTotalItems(Exporter $exporter): int
    {
        $total = 0;

        foreach ($exporter->getCounters() as $counter) {
            $value = str_replace(',', '', (string) $counter->getValue());

            if (is_numeric($value) && (int) $value > $total) {
                $total = (int) $value;
            }
        }

        return $total;
    }

    protected function streamingExport(Exporter $exporter, ExportRequest $request): StreamedResponse
    {
        $fileName = Str::replaceLast('.xlsx', '.csv', $exporter->getExportFileName());
        $columns = $exporter->getAcceptedColumns();

        return response()->streamDownload(function () use ($exporter, $columns): void {
            set_time_limit(0);
            ini_set('memory_limit', config('packages.data-synchronize.data-synchronize.export.memory_limit', '512M'));

            DB::disableQueryLog();

            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens the file with the right encoding.
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, array_map(fn (ExportColumn $column) => $column->getLabel(), $columns));

            $written = 0;

            // The exporter owns the query and the row shape - this controller only
            // turns the rows it yields into CSV, so every exporter streams correctly.
            foreach ($exporter->streamingGenerator() as $row) {
                fputcsv($handle, array_map(
                    fn (ExportColumn $column) => $this->formatStreamedValue(Arr::get($row, $column->getName())),
                    $columns
                ));

                if (++$written % 1000 === 0) {
                    flush();
                    gc_collect_cycles();
                }
            }

            flush();
            fclose($handle);

            DB::enableQueryLog();
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Flatten one exported value into something fputcsv() can write.
     */
    protected function formatStreamedValue(mixed $value): string
    {
        return match (true) {
            $value === null, $value === false => '',
            $value === true => '1',
            $value instanceof BackedEnum => (string) $value->value,
            $value instanceof DateTimeInterface => $value->format('Y-m-d H:i:s'),
            is_array($value) => implode(',', $value),
            $value instanceof Arrayable => implode(',', $value->toArray()),
            default => (string) $value,
        };
    }

    protected function streamExport(Exporter $exporter, ExportRequest $request): StreamedResponse
    {
        $fileName = $exporter->getExportFileName();
        $format = $request->input('format', 'xlsx');

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->export()->getFile()->getContent();
        }, $fileName, [
            'Content-Type' => match ($format) {
                'csv' => 'text/csv',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                default => 'application/octet-stream',
            },
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
