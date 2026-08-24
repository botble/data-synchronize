<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Tests\TestCase;

class ExporterTest extends TestCase
{
    protected function exporter(): SampleExporter
    {
        return new SampleExporter();
    }

    public function test_label_is_derived_from_the_class_name(): void
    {
        $this->assertSame('Sample', $this->exporter()->getLabel());
    }

    public function test_headings_come_from_the_accepted_column_labels(): void
    {
        $this->assertSame(
            ['ID', 'Name', 'Is Featured', 'Created At'],
            $this->exporter()->headings()
        );
    }

    public function test_map_pulls_values_by_column_name(): void
    {
        $row = $this->exporter()->map(['id' => 7, 'name' => 'Widget', 'is_featured' => false, 'created_at' => null]);

        $this->assertSame(7, $row[0]);
        $this->assertSame('Widget', $row[1]);
    }

    public function test_map_renders_booleans_with_the_configured_labels(): void
    {
        $exporter = $this->exporter();

        $this->assertSame('Yes', $exporter->map(['is_featured' => true])[2]);
        $this->assertSame('No', $exporter->map(['is_featured' => false])[2]);
        $this->assertSame('No', $exporter->map([])[2]);
    }

    public function test_map_converts_datetimes_to_excel_serial_numbers(): void
    {
        $value = $this->exporter()->map(['created_at' => '2026-08-24 10:00:00'])[3];

        $this->assertIsFloat($value);
        $this->assertGreaterThan(40000, $value);
    }

    public function test_map_leaves_an_empty_datetime_blank_instead_of_failing(): void
    {
        $exporter = $this->exporter();

        $this->assertSame('', $exporter->map(['created_at' => null])[3]);
        $this->assertSame('', $exporter->map(['created_at' => ''])[3]);
        $this->assertSame('', $exporter->map([])[3]);
    }

    public function test_map_accepts_a_datetime_instance(): void
    {
        $value = $this->exporter()->map(['created_at' => new \DateTimeImmutable('2026-08-24 10:00:00')])[3];

        $this->assertIsFloat($value);
    }

    public function test_map_passes_an_unparseable_datetime_through_untouched(): void
    {
        $this->assertSame('not a date', $this->exporter()->map(['created_at' => 'not a date'])[3]);
    }

    public function test_accepted_columns_defaults_to_every_column(): void
    {
        $this->assertCount(4, $this->exporter()->getAcceptedColumns());
    }

    public function test_accepted_columns_can_be_narrowed_by_name(): void
    {
        $names = array_map(
            fn (ExportColumn $column) => $column->getName(),
            $this->exporter()->acceptedColumns(['name'])->getAcceptedColumns()
        );

        $this->assertSame(['name'], $names);
    }

    public function test_disabled_columns_are_always_included_even_when_not_selected(): void
    {
        $names = array_map(
            fn (ExportColumn $column) => $column->getName(),
            (new SampleExporterWithRequiredColumn())->acceptedColumns(['name'])->getAcceptedColumns()
        );

        $this->assertContains('id', $names, 'A disabled column is required and must survive column selection.');
        $this->assertContains('name', $names);
    }

    public function test_all_columns_is_disabled_reports_whether_selection_is_possible(): void
    {
        $this->assertFalse($this->exporter()->allColumnsIsDisabled());
        $this->assertTrue((new FullyDisabledExporter())->allColumnsIsDisabled());
    }

    public function test_format_is_xlsx_by_default(): void
    {
        $exporter = $this->exporter();

        $this->assertSame('xlsx', $exporter->getFormat());
        $this->assertFalse($exporter->isCsv());
    }

    public function test_format_accepts_the_excel_constants_and_normalises_them(): void
    {
        // Excel::CSV is 'Csv' and Excel::XLSX is 'Xlsx'; both must work.
        $this->assertSame('csv', $this->exporter()->format(Excel::CSV)->getFormat());
        $this->assertSame('xlsx', $this->exporter()->format(Excel::XLSX)->getFormat());
        $this->assertSame('csv', $this->exporter()->format('csv')->getFormat());
        $this->assertTrue($this->exporter()->format(Excel::CSV)->isCsv());
    }

    public function test_export_file_name_uses_a_lowercase_extension(): void
    {
        $this->assertStringEndsWith('.xlsx', $this->exporter()->getExportFileName());
        $this->assertStringEndsWith('.csv', $this->exporter()->format(Excel::CSV)->getExportFileName());
        $this->assertStringStartsWith('sample-', $this->exporter()->getExportFileName());
    }

    public function test_column_formats_are_skipped_for_csv(): void
    {
        $this->assertSame([], $this->exporter()->format('csv')->columnFormats());
    }

    public function test_column_formats_are_returned_for_spreadsheets(): void
    {
        $formats = $this->exporter()->format('xlsx')->columnFormats();

        $this->assertSame(NumberFormat::FORMAT_TEXT, $formats['A']);
        $this->assertSame('yyyy-mm-dd hh:mm:ss', $formats['D'], 'Datetime columns keep their own format.');
    }

    public function test_sheet_events_are_skipped_for_csv(): void
    {
        $this->assertSame([], $this->exporter()->format('csv')->registerEvents());
        $this->assertNotSame([], $this->exporter()->format('xlsx')->registerEvents());
    }

    public function test_counters_are_empty_unless_the_exporter_defines_them(): void
    {
        $this->assertSame([], (new FullyDisabledExporter())->getCounters());
        $this->assertCount(1, $this->exporter()->getCounters());
        $this->assertSame('Total', $this->exporter()->getCounters()[0]->getLabel());
    }

    public function test_has_data_to_export_defaults_to_true(): void
    {
        $this->assertTrue($this->exporter()->hasDataToExport());
    }

    public function test_url_can_be_overridden(): void
    {
        $this->assertSame('https://example.test/export', $this->exporter()->url('https://example.test/export')->getUrl());
    }
}

class SampleExporter extends Exporter
{
    public function columns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('is_featured')->boolean(),
            ExportColumn::make('created_at')->dateTime(),
        ];
    }

    public function counters(): array
    {
        return [ExportCounter::make()->label('Total')->value(10)];
    }

    public function collection(): Collection
    {
        return collect();
    }
}

class SampleExporterWithRequiredColumn extends Exporter
{
    public function columns(): array
    {
        return [
            ExportColumn::make('id')->disabled(),
            ExportColumn::make('name'),
        ];
    }

    public function collection(): Collection
    {
        return collect();
    }
}

class FullyDisabledExporter extends Exporter
{
    public function columns(): array
    {
        return [ExportColumn::make('id')->disabled()];
    }

    public function collection(): Collection
    {
        return collect();
    }
}
