<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Exporter\ExampleExporter;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Importer\ImportColumn;
use Tests\TestCase;

class ExporterSupportTest extends TestCase
{
    protected function exampleExporter(): ExampleExporter
    {
        return new ExampleExporter(
            [['name' => 'Ann', 'email' => 'ann@example.com']],
            [ImportColumn::make('name'), ImportColumn::make('email')->label('Email address')],
            'Customer'
        );
    }

    public function test_example_exporter_mirrors_the_importer_columns(): void
    {
        $columns = $this->exampleExporter()->columns();

        $this->assertSame(['name', 'email'], array_map(fn (ExportColumn $c) => $c->getName(), $columns));
        $this->assertSame(['Name', 'Email address'], array_map(fn (ExportColumn $c) => $c->getLabel(), $columns));
    }

    public function test_example_exporter_names_the_file_after_the_importer_label(): void
    {
        $this->assertSame('Customer-example.xlsx', $this->exampleExporter()->getExportFileName());
        $this->assertSame('Customer-example.csv', $this->exampleExporter()->format('csv')->getExportFileName());
    }

    public function test_example_exporter_turns_a_multiword_label_into_a_safe_file_name(): void
    {
        $exporter = new ExampleExporter([], [ImportColumn::make('name')], ' Product Category ');

        $this->assertSame('Product-Category-example.xlsx', $exporter->getExportFileName());
    }

    public function test_example_exporter_exposes_the_sample_rows(): void
    {
        $rows = $this->exampleExporter()->collection();

        $this->assertCount(1, $rows);
        $this->assertSame('Ann', $rows->first()->name);
    }

    public function test_example_exporter_maps_sample_rows_onto_the_columns(): void
    {
        $this->assertSame(['Ann', 'ann@example.com'], $this->exampleExporter()->map((object) [
            'name' => 'Ann',
            'email' => 'ann@example.com',
        ]));
    }

    public function test_empty_state_provides_a_title_description_icon_and_action(): void
    {
        $exporter = $this->exampleExporter();

        $this->assertNotEmpty($exporter->getEmptyStateTitle());
        $this->assertNotEmpty($exporter->getEmptyStateDescription());
        $this->assertSame('ti ti-mood-empty', $exporter->getEmptyStateIcon());
        $this->assertNotEmpty($exporter->getEmptyStateActionLabel());
        $this->assertSame(route('tools.data-synchronize'), $exporter->getEmptyStateActionUrl());
    }
}
