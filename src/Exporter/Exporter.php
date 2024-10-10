<?php

namespace Botble\DataSynchronize\Exporter;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\BaseHelper;
use Botble\DataSynchronize\Concerns\Exporter\HasEmptyState;
use Botble\DataSynchronize\Enums\ExportColumnType;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class Exporter implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping
{
    use HasEmptyState;

    protected ?array $acceptedColumns = [];

    protected string $format = Excel::XLSX;

    protected string $url;

    /**
     * @return \Botble\DataSynchronize\Exporter\ExportColumn[]
     */
    abstract public function columns(): array;

    public function counters(): array
    {
        return [];
    }

    public function getLabel(): string
    {
        return str(static::class)
            ->afterLast('\\')
            ->snake()
            ->replace('_', ' ')
            ->remove('exporter')
            ->title();
    }

    public function getHeading(): string
    {
        return trans(
            'packages/data-synchronize::data-synchronize.export.heading',
            ['label' => $this->getLabel()]
        );
    }

    public function getLayout(): string
    {
        return BaseHelper::getAdminMasterLayoutTemplate();
    }

    /**
     * @return \Botble\DataSynchronize\Exporter\ExportCounter[]
     */
    public function getCounters(): array
    {
        return apply_filters('data_synchronize_exporter_counters', $this->counters(), $this);
    }

    public function hasDataToExport(): bool
    {
        return true;
    }

    public function headings(): array
    {
        return array_map(fn (ExportColumn $column) => $column->getLabel(), $this->getAcceptedColumns());
    }

    public function map($row): array
    {
        return array_map(function (ExportColumn $column) use ($row) {
            $value = Arr::get((array) $row, $column->getName());

            return match ($column->getType()) {
                ExportColumnType::BOOLEAN => $value ? $column->getTrueValue() : $column->getFalseValue(),
                ExportColumnType::DATETIME => Date::dateTimeToExcel($value),
                default => $value,
            };
        }, $this->getAcceptedColumns());
    }

    public function columnFormats(): array
    {
        if ($this->format === Excel::CSV) {
            return [];
        }

        $formattedColumns = [];

        foreach ($this->getAcceptedColumns() as $index => $column) {
            $coordinate = Coordinate::stringFromColumnIndex($index + 1);

            $formattedColumns[$coordinate] = match ($column->getType()) {
                ExportColumnType::DATETIME => $column->getDateTimeFormat(),
                default => NumberFormat::FORMAT_TEXT,
            };
        }

        return $formattedColumns;
    }

    public function registerEvents(): array
    {
        if ($this->format === Excel::CSV) {
            return [];
        }

        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($this->getAcceptedColumns() as $key => $column) {
                    if (in_array($column->getType(), [ExportColumnType::DROPDOWN, ExportColumnType::BOOLEAN])) {
                        $coordinate = Coordinate::stringFromColumnIndex($key + 1);

                        $validation = new DataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                        $validation->setAllowBlank(false);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setShowDropDown(true);
                        if ($column->getValidationErrorTitle()) {
                            $validation->setErrorTitle($column->getValidationErrorTitle());
                        }
                        if ($column->getValidationError()) {
                            $validation->setError($column->getValidationError());
                        }
                        if ($column->getValidationPromptTitle()) {
                            $validation->setPromptTitle($column->getValidationPromptTitle());
                        }
                        if ($column->getValidationPrompt()) {
                            $validation->setPrompt($column->getValidationPrompt());
                        }
                        $validation->setFormula1(sprintf('"%s"', implode(',', $column->getOptions())));

                        foreach (range(2, count($event->sheet->getDelegate()->toArray())) as $index) {
                            $event->sheet->getCell("{$coordinate}{$index}")->setDataValidation($validation);
                        }
                    }
                }
            },
        ];
    }

    public static function make(): static
    {
        return new static();
    }

    /**
     * @return \Botble\DataSynchronize\Exporter\ExportColumn[]
     */
    public function getColumns(): array
    {
        return apply_filters('data_synchronize_exporter_columns', $this->columns());
    }

    public function getExportFileName(): string
    {
        return sprintf(
            '%s-%s.%s',
            Str::slug($this->getLabel()),
            BaseHelper::formatDateTime(Carbon::now(), 'Y-m-d-H-i-s'),
            $this->format
        );
    }

    public function render(): View
    {
        Assets::addScriptsDirectly('vendor/core/packages/data-synchronize/js/data-synchronize.js');

        return view('packages/data-synchronize::export', [
            'exporter' => $this,
        ]);
    }

    public function export(): BinaryFileResponse
    {
        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        $writeType = match ($this->format) {
            'csv' => Excel::CSV,
            'xlsx' => Excel::XLSX,
        };

        $headers = [
            'Content-Type' => match ($this->format) {
                'csv' => 'text/csv',
                'xlsx' => 'text/xlsx',
            },
        ];

        return ExcelFacade::download($this, $this->getExportFileName(), $writeType, $headers);
    }

    public function acceptedColumns(?array $columns): self
    {
        $this->acceptedColumns = $columns;

        return $this;
    }

    /**
     * @return \Botble\DataSynchronize\Exporter\ExportColumn[]
     */
    public function getAcceptedColumns(): array
    {
        $columns = $this->columns();
        $requiredColumns = array_filter($this->columns(), fn (ExportColumn $column) => $column->isDisabled());

        if ($this->acceptedColumns) {
            return [
                ...$requiredColumns,
                ...array_filter($columns, fn (ExportColumn $column) => in_array($column->getName(), $this->acceptedColumns)),
            ];
        }

        return $columns;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        if (isset($this->url)) {
            return $this->url;
        }

        return url()->current();
    }

    public function allColumnsIsDisabled(): bool
    {
        return count($this->getAcceptedColumns()) === count(array_filter($this->getAcceptedColumns(), fn (ExportColumn $column) => $column->isDisabled()));
    }
}
