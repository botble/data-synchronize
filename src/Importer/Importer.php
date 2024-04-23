<?php

namespace Botble\DataSynchronize\Importer;

use Botble\Base\Facades\Assets;
use Botble\DataSynchronize\DataTransferObjects\ChunkImportResponse;
use Botble\DataSynchronize\DataTransferObjects\ChunkValidateResponse;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\Exporter;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

abstract class Importer
{
    abstract public function columns(): array;

    abstract public function getValidateUrl(): string;

    abstract public function getImportUrl(): string;

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

    public function getColumns(): array
    {
        return apply_filters('data_synchronize_importer_columns', $this->columns());
    }

    public function getExamples(): array
    {
        return apply_filters('data_synchronize_importer_example', $this->examples());
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

    public function getHeading(): string
    {
        return trans(
            'packages/data-synchronize::data-synchronize.import.heading',
            ['label' => $this->getLabel()]
        );
    }

    public function render(): View
    {
        Assets::addStylesDirectly('vendor/core/packages/data-synchronize/css/data-synchronize.css')
            ->addScriptsDirectly('vendor/core/packages/data-synchronize/js/data-synchronize.js')
            ->addScripts('dropzone')
            ->addStyles('dropzone');

        return view(
            apply_filters('data_synchronize_importer_view', 'packages/data-synchronize::import'),
            ['importer' => $this]
        );
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

            $this->filesystem()->move("$storageFolder/{$fileName}", "$storageFolder/{$newFileName}");
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

        $imported = $this->handle($this->transformRows($rows));

        if ($count === 0) {
            $storageFolder = config('packages.data-synchronize.data-synchronize.storage.path');

            $this->filesystem()->delete("$storageFolder/$fileName");
        }

        return new ChunkImportResponse(
            offset: $offset,
            count: $count,
            imported: $imported,
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
            'label' => $this->getLabel(),
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

        $reader = SimpleExcelReader::create($this->filesystem()->path($filePath))
            ->headersToSnakeCase();

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
        return array_map(fn ($row) => collect($this->getColumns())
            ->mapWithKeys(function (ImportColumn $column) use ($row) {
                $value = $row[$column->getName()] ?? null;

                if ($column->isNullable() && empty($value)) {
                    return [$column->getName() => null];
                }

                if ($column->isBoolean() && is_string($value)) {
                    $value = $value === $column->getTrueValue() ? 1 : 0;
                }

                return [$column->getName() => $value];
            })
            ->all(), $rows);
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

    public function downloadExample(string $format)
    {
        $examples = $this->getExamples();
        $columns = $this->getColumns();
        $label = $this->getLabel();

        $exporter = new class($examples, $columns, $label) extends Exporter
        {
            public function __construct(protected array $examples, protected array $columns, protected string $label)
            {
            }

            public function columns(): array
            {
                return array_map(fn (ImportColumn $item) => ExportColumn::make($item->getName())->label($item->getLabel()), $this->columns);
            }

            public function getExportFileName(): string
            {
                return sprintf('%s-example', str($this->label)->trim()->replace(' ', '-'));
            }

            public function collection(): Collection
            {
                return collect($this->examples)->map(fn ($item) => (object) $item);
            }
        };

        return $exporter
            ->format($format)
            ->acceptedColumns(array_map(fn (ImportColumn $column) => $column->getName(), $columns))
            ->export();
    }
}
