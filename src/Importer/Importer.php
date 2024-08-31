<?php

namespace Botble\DataSynchronize\Importer;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\DataSynchronize\Concerns\Importer\HasImportResults;
use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\DataTransferObjects\ChunkImportResponse;
use Botble\DataSynchronize\DataTransferObjects\ChunkValidateResponse;
use Botble\DataSynchronize\Exporter\ExampleExporter;
use Botble\Media\Facades\RvMedia;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class Importer
{
    use HasImportResults;

    protected bool $renderWithoutLayout = false;

    abstract public function columns(): array;

    abstract public function getValidateUrl(): string;

    abstract public function getImportUrl(): string;

    public function getUploadUrl(): string
    {
        return route('data-synchronize.upload');
    }

    abstract public function handle(array $data): int;

    public function getLabel(): string
    {
        return str(static::class)
            ->afterLast('\\')
            ->snake()
            ->replace('_', ' ')
            ->remove('importer')
            ->trim()
            ->title();
    }

    public static function make(): static
    {
        return new static();
    }

    public function examples(): array
    {
        return [];
    }

    public function getLayout(): string
    {
        return BaseHelper::getAdminMasterLayoutTemplate();
    }

    public function getColumns(): array
    {
        return apply_filters('data_synchronize_importer_columns', $this->columns());
    }

    public function getExamples(): array
    {
        return apply_filters('data_synchronize_importer_example', $this->examples());
    }

    public function showRulesCheatSheet(): bool
    {
        return ! empty(array_filter($this->getValidationRules()));
    }

    public function getAcceptedFiles(): array
    {
        return apply_filters(
            'data_synchronize_importer_accepted_files',
            config('packages.data-synchronize.data-synchronize.mime_types')
        );
    }

    public function getFileExtensions(): array
    {
        return apply_filters(
            'data_synchronize_importer_file_extensions',
            config('packages.data-synchronize.data-synchronize.extensions')
        );
    }

    public function chunkSize(): int
    {
        return apply_filters('data_synchronize_importer_chunk_size', 1000);
    }

    public function getExportUrl(): ?string
    {
        return null;
    }

    public function getDownloadExampleUrl(): ?string
    {
        return null;
    }

    public function mergeWithUndefinedColumns(): bool
    {
        return false;
    }

    public function getHeading(): string
    {
        return trans(
            'packages/data-synchronize::data-synchronize.import.heading',
            ['label' => $this->getLabel()]
        );
    }

    public function renderWithoutLayout(): View
    {
        $this->renderWithoutLayout = true;

        return $this->render();
    }

    public function render(): View
    {
        Assets::addStylesDirectly('vendor/core/packages/data-synchronize/css/data-synchronize.css')
            ->addScriptsDirectly('vendor/core/packages/data-synchronize/js/data-synchronize.js')
            ->addScripts('dropzone')
            ->addStyles('dropzone');

        $view = 'packages/data-synchronize::import';

        if ($this->renderWithoutLayout) {
            $view = 'packages/data-synchronize::partials.importer';
        }

        return view(
            apply_filters('data_synchronize_importer_view', $view),
            ['importer' => $this]
        );
    }

    public function headerToSnakeCase(): bool
    {
        return true;
    }

    public function validate(string $fileName, int $offset = 0, int $limit = 100): ChunkValidateResponse
    {
        $rows = $this->transformRows($this->getRowsByOffset($fileName, $offset, $limit));

        $total = request()->integer('total') ?: $this->getRows($fileName)->count();

        $validator = Validator::make($rows, $this->getValidationRules());

        $errors = [];

        if ($validator->fails()) {
            $errors = array_map(fn ($error) => $error[0], $validator->errors()->toArray());
        }

        $count = count($rows);

        if ($count === 0) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . uniqid() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

            $storageFolder = config('packages.data-synchronize.data-synchronize.storage.path');

            if ($this->filesystem()->exists("$storageFolder/{$fileName}")) {
                $this->filesystem()->move("$storageFolder/{$fileName}", "$storageFolder/{$newFileName}");
            }
        }

        return new ChunkValidateResponse(
            offset: $offset,
            count: $count,
            total: $total,
            fileName: $count === 0 ? $newFileName : $fileName,
            errors: array_values($errors),
        );
    }

    public function import(string $fileName, int $offset = 0, int $limit = 100): ChunkImportResponse
    {
        $rows = $this->getRowsByOffset($fileName, $offset, $limit);
        $count = count($rows);
        $rows = $this->transformRows($rows);

        $rowNumber = 0;
        $rows = array_filter($rows, function () use (&$rowNumber) {
            $rowNumber++;

            return ! in_array($rowNumber, $this->failures()->map(fn ($failure) => $failure['row'])->all());
        });

        $imported = $this->handle($rows);

        if ($count === 0) {
            $storageFolder = config('packages.data-synchronize.data-synchronize.storage.path');

            if ($this->filesystem()->exists("$storageFolder/$fileName")) {
                $this->filesystem()->delete("$storageFolder/$fileName");
            }
        }

        return new ChunkImportResponse(
            offset: $offset,
            count: $count,
            imported: $imported,
            failures: $this->failures()->all()
        );
    }

    public function getImportingMessage(int $from, int $to): string
    {
        return trans('packages/data-synchronize::data-synchronize.import.importing_message', [
            'from' => number_format($from),
            'to' => number_format($to),
        ]);
    }

    public function getDoneMessage(int $count): string
    {
        return trans('packages/data-synchronize::data-synchronize.import.done_message', [
            'count' => number_format($count),
            'label' => strtolower($this->getLabel()),
        ]);
    }

    public function getRowsByOffset(string $fileName, int $offset = 0, int $limit = 100): array
    {
        return $this->getRows($fileName, $offset, $limit)->all();
    }

    public function getRows(string $fileName, int $offset = 0, int $limit = 0): LazyCollection
    {
        $filePath = sprintf('%s/%s', config('packages.data-synchronize.data-synchronize.storage.path'), $fileName);

        if (! $this->filesystem()->exists($filePath)) {
            throw new FileNotFoundException('File not found at path: ' . $filePath);
        }

        $reader = SimpleExcelReader::create($this->filesystem()->path($filePath));

        if ($this->headerToSnakeCase()) {
            $reader->headersToSnakeCase();
        }

        if ($offset > 0) {
            $reader->skip($offset);
        }

        if ($limit > 0) {
            $reader->take($limit);
        }

        return $reader->getRows();
    }

    public function transformRows(array $rows): array
    {
        return array_map(function ($row) {
            $formatted = collect($this->getColumns())
                ->mapWithKeys(function (ImportColumn $column) use ($row) {
                    $value = Arr::pull($row, $column->getHeading());

                    $value = match (true) {
                        $column->isNullable() && empty($value) => null,
                        $column->isBoolean() && is_string($value) => $value === $column->getTrueValue() ? 1 : 0,
                        default => $value,
                    };

                    return [$column->getName() => $value];
                })
                ->all();

            return [
                ...($this instanceof WithMapping ? $this->map($formatted) : $formatted),
                ...($this->mergeWithUndefinedColumns() ? $row : []),
            ];
        }, $rows);
    }

    public function getValidationRules(): array
    {
        $rules = collect($this->getColumns())
            ->mapWithKeys(fn (ImportColumn $column) => ["*.{$column->getName()}" => $column->getRules()])
            ->all();

        return apply_filters('data_synchronize_importer_validation_rules', $rules);
    }

    public function filesystem(): Filesystem
    {
        return Storage::disk(config('packages.data-synchronize.data-synchronize.storage.disk'));
    }

    public function downloadExample(string $format): BinaryFileResponse
    {
        $columns = $this->getColumns();
        $exporter = (new ExampleExporter($this->getExamples(), $columns, $this->getLabel()));

        return $exporter
            ->format($format)
            ->acceptedColumns(array_map(fn (ImportColumn $column) => $column->getName(), $columns))
            ->export();
    }

    protected function resolveMediaImage(string $url, ?string $directory = null): string
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        $result = RvMedia::uploadFromUrl($url, 0, $directory);

        if ($result['error']) {
            Log::error($result['message']);

            return $url;
        }

        return $result['data']->url;
    }
}
