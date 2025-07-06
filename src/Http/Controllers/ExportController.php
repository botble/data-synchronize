<?php

namespace Botble\DataSynchronize\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Requests\ExportRequest;
use Botble\Ecommerce\Models\Product;
use Botble\Media\Facades\RvMedia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

abstract class ExportController extends BaseController
{
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

            if (method_exists($exporter, 'getCounters')) {
                $counters = $exporter->getCounters();
                $totalItems = 0;

                foreach ($counters as $counter) {
                    if (str_contains(strtolower($counter->getLabel()), 'total')) {
                        $value = str_replace(',', '', $counter->getValue());
                        if (is_numeric($value) && $value > $totalItems) {
                            $totalItems = (int) $value;
                        }
                    }
                }

                if ($totalItems > 10000 && $request->input('format') === 'xlsx') {
                    return $this
                        ->httpResponse()
                        ->setError()
                        ->setMessage(trans('packages/data-synchronize::data-synchronize.export.excel_not_supported_for_large_exports', ['count' => number_format($totalItems)]));
                }
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

                if (! $enableStreaming && method_exists($exporter, 'getCounters')) {
                    $counters = $exporter->getCounters();
                    $totalItems = 0;

                    foreach ($counters as $counter) {
                        if (str_contains(strtolower($counter->getLabel()), 'total')) {
                            $value = str_replace(',', '', $counter->getValue());
                            if (is_numeric($value) && $value > $totalItems) {
                                $totalItems = (int) $value;
                            }
                        }
                    }

                    $enableStreaming = $totalItems > 10000;
                }

                if ($enableStreaming) {
                    $exporter->enableStreamingMode(true);
                }
            }

            if ($request->boolean('stream', false)) {
                return $this->streamExport($exporter, $request);
            }

            if (method_exists($exporter, 'isStreamingMode') && $exporter->isStreamingMode() && $request->input('format') === 'csv') {
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

    protected function streamingExport(Exporter $exporter, ExportRequest $request): StreamedResponse
    {
        $fileName = str_replace('.xlsx', '.csv', $exporter->getExportFileName());

        return response()->streamDownload(function () use ($exporter, $request) {
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $columns = $exporter->getAcceptedColumns();
            $headers = collect($columns)->pluck('label')->toArray();
            fputcsv($handle, $headers);

            \DB::disableQueryLog();

            $query = Product::query()
                ->select('*')
                ->with([
                    'categories:id,name',
                    'brand:id,name',
                    'productCollections:id,name',
                    'productLabels:id,name',
                    'productAttributeSets:id,title',
                    'tags:id,name',
                    'taxes:id,title',
                ])
                ->where('is_variation', 0)
                ->orderBy('id');

            $includeVariations = $request->boolean('include_variations', true);
            $processedCount = 0;

            $query->chunk($request->integer('chunk_size', 300), function ($products) use ($handle, $exporter, $columns, $includeVariations, &$processedCount) {
                foreach ($products as $product) {
                    $row = [];
                    foreach ($columns as $column) {
                        $columnName = $column->getName();

                        switch ($columnName) {
                            case 'categories':
                                $row[] = $product->categories->pluck('name')->implode(',');

                                break;
                            case 'brand':
                                $row[] = $product->brand?->name ?? '';

                                break;
                            case 'product_collections':
                                $row[] = $product->productCollections->pluck('name')->implode(',');

                                break;
                            case 'labels':
                                $row[] = $product->productLabels->pluck('name')->implode(',');

                                break;
                            case 'taxes':
                                $row[] = $product->taxes->pluck('title')->implode(',');

                                break;
                            case 'image':
                                $row[] = RvMedia::getImageUrl($product->image);

                                break;
                            case 'images':
                                $row[] = collect($product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(',');

                                break;
                            case 'tags':
                                $row[] = $product->tags->pluck('name')->implode(',');

                                break;
                            case 'url':
                                $row[] = $product->url;

                                break;
                            case 'status':
                                $row[] = $product->status->getValue();

                                break;
                            case 'stock_status':
                                $row[] = $product->stock_status->getValue();

                                break;
                            case 'import_type':
                                $row[] = 'product';

                                break;
                            case 'product_attributes':
                                $row[] = $product->is_variation ? '' : $product->productAttributeSets->pluck('title')->implode(',');

                                break;
                            default:
                                $row[] = $product->{$columnName} ?? '';

                                break;
                        }
                    }

                    fputcsv($handle, $row);
                    $processedCount++;

                    if ($includeVariations && $product->variations) {
                        $product->load(['variations.product', 'variations.productAttributes.productAttributeSet']);

                        foreach ($product->variations as $variation) {
                            $varRow = [];
                            foreach ($columns as $column) {
                                $columnName = $column->getName();

                                switch ($columnName) {
                                    case 'id':
                                        $varRow[] = $variation->product->id;

                                        break;
                                    case 'name':
                                        $varRow[] = $variation->product->name;

                                        break;
                                    case 'sku':
                                        $varRow[] = $variation->product->sku;

                                        break;
                                    case 'price':
                                        $varRow[] = $variation->product->price;

                                        break;
                                    case 'sale_price':
                                        $varRow[] = $variation->product->sale_price;

                                        break;
                                    case 'quantity':
                                        $varRow[] = $variation->product->quantity;

                                        break;
                                    case 'image':
                                        $varRow[] = RvMedia::getImageUrl($variation->product->image);

                                        break;
                                    case 'images':
                                        $varRow[] = collect($variation->product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(',');

                                        break;
                                    case 'status':
                                        $varRow[] = $variation->product->status->getValue();

                                        break;
                                    case 'stock_status':
                                        $varRow[] = $variation->product->stock_status->getValue();

                                        break;
                                    case 'import_type':
                                        $varRow[] = 'variation';

                                        break;
                                    case 'is_variation_default':
                                        $varRow[] = $variation->is_default;

                                        break;
                                    case 'product_attributes':
                                        $attrs = [];
                                        foreach ($variation->productAttributes as $attr) {
                                            $attrs[] = $attr->productAttributeSet->title . ':' . $attr->title;
                                        }
                                        $varRow[] = implode(',', $attrs);

                                        break;
                                    case 'weight':
                                        $varRow[] = $variation->product->weight;

                                        break;
                                    case 'length':
                                        $varRow[] = $variation->product->length;

                                        break;
                                    case 'wide':
                                        $varRow[] = $variation->product->wide;

                                        break;
                                    case 'height':
                                        $varRow[] = $variation->product->height;

                                        break;
                                    case 'with_storehouse_management':
                                        $varRow[] = $variation->product->with_storehouse_management;

                                        break;
                                    case 'allow_checkout_when_out_of_stock':
                                        $varRow[] = $variation->product->allow_checkout_when_out_of_stock;

                                        break;
                                    case 'barcode':
                                        $varRow[] = $variation->product->barcode;

                                        break;
                                    case 'cost_per_item':
                                        $varRow[] = $variation->product->cost_per_item;

                                        break;
                                    case 'minimum_order_quantity':
                                        $varRow[] = $variation->product->minimum_order_quantity;

                                        break;
                                    case 'maximum_order_quantity':
                                        $varRow[] = $variation->product->maximum_order_quantity;

                                        break;
                                    case 'categories':
                                    case 'brand':
                                    case 'product_collections':
                                    case 'labels':
                                    case 'taxes':
                                    case 'tags':
                                    case 'description':
                                    case 'content':
                                    case 'slug':
                                    case 'url':
                                    case 'is_featured':
                                        $varRow[] = '';

                                        break;
                                    default:
                                        $varRow[] = $variation->product->{$columnName} ?? '';

                                        break;
                                }
                            }

                            fputcsv($handle, $varRow);
                            $processedCount++;
                        }
                    }
                }

                if ($processedCount % 1000 === 0) {
                    gc_collect_cycles();
                }

                flush();
            });

            \DB::enableQueryLog();

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
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
