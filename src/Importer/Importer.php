<?php

namespace Botble\DataSynchronize\Importer;

use Botble\Base\Facades\Assets;
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
    protected array $acceptedFiles = ['csv', 'xls', 'xlsx'];

    abstract public function columns(): array;

    abstract public function getValidateUrl(): string;

    abstract public function getImportUrl(): string;

    abstract public function array(array $data): int;

    public function getLabel(): string
    {
        return str(static::class)
            ->afterLast('\\')
            ->snake()
            ->replace('_', ' ')
            ->remove('importer')
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
        return apply_filters('data_synchronize_importer_accepted_files', $this->acceptedFiles);
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

    public function validate(string $fileName, int $offset = 0, int $limit = 100): array
    {
        $rows = $this->transformRows($this->getRowsByOffset($fileName, $offset, $limit));

        $total = request()->integer('total') ?: $this->getRows($fileName)->count();

        $validator = Validator::make($rows, $this->getValidationRules());

        $errors = [];

        if ($validator->fails()) {
            $errors = array_map(fn ($error) => $error[0], $validator->errors()->toArray());
        }

        $rowsCount = count($rows);

        if ($rowsCount === 0) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . uniqid() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

            $this->filesystem()->move("uploads/{$fileName}", "uploads/{$newFileName}");
        }

        $from = $offset + 1;
        $to = $offset + $rowsCount;

        return [
            'total' => $total,
            'offset' => $offset,
            'count' => $rowsCount,
            'errors' => array_values($errors),
            'file_name' => $rowsCount === 0 ? $newFileName : $fileName,
            'message' => $from <= $to ? trans('packages/data-synchronize::data-synchronize.import.validating_message', [
                'from' => number_format($from),
                'to' => number_format($to),
            ]) : null,
        ];
    }

    public function import(string $fileName, int $offset = 0, int $limit = 100): array
    {
        $rows = $this->getRowsByOffset($fileName, $offset, $limit);

        $rowsCount = count($rows);
        $from = $offset + 1;
        $to = $offset + $rowsCount;

        $imported = $this->array($this->transformRows($rows));

        $total = request()->integer('total') + $imported;

        if ($from <= $to) {
            $message = $this->getImportingMessage($from, $to);
        } else {
            if ($total > 0) {
                $message = $this->getDoneMessage($total);
            } else {
                $message = trans('packages/data-synchronize::data-synchronize.import.no_data_message', [
                    'label' => $this->getLabel(),
                ]);
            }
        }

        if (count($rows) === 0) {
            $this->filesystem()->delete("uploads/{$fileName}");
        }

        return [
            'offset' => $offset,
            'count' => $rowsCount,
            'message' => $message,
            'total' => $total,
        ];
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
        $filePath = sprintf('uploads/%s', $fileName);

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
        return Storage::disk('local');
    }

    public function downloadExample(string $format)
    {
        $examples = $this->getExamples();
        $columns = $this->getColumns();
        $label = $this->getLabel();

        $exporter = new class ($examples, $columns, $label) extends Exporter {
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
