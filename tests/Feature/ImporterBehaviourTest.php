<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Tests\TestCase;

class ImporterBehaviourTest extends TestCase
{
    public function test_label_is_derived_from_the_class_name_without_the_suffix(): void
    {
        $this->assertSame('Behaviour Test', (new BehaviourTestImporter())->getLabel());
    }

    public function test_heading_wraps_the_label(): void
    {
        $this->assertStringContainsString('Behaviour Test', (new BehaviourTestImporter())->getHeading());
    }

    public function test_make_returns_a_new_instance(): void
    {
        $this->assertInstanceOf(BehaviourTestImporter::class, BehaviourTestImporter::make());
        $this->assertNotSame(BehaviourTestImporter::make(), BehaviourTestImporter::make());
    }

    public function test_accepted_files_and_extensions_come_from_config(): void
    {
        $importer = new BehaviourTestImporter();

        $this->assertSame(
            config('packages.data-synchronize.data-synchronize.mime_types'),
            $importer->getAcceptedFiles()
        );
        $this->assertSame(
            config('packages.data-synchronize.data-synchronize.extensions'),
            $importer->getFileExtensions()
        );
        $this->assertContains('csv', $importer->getFileExtensions());
    }

    public function test_chunk_size_has_a_default(): void
    {
        $this->assertSame(1000, (new BehaviourTestImporter())->chunkSize());
    }

    public function test_headers_are_snake_cased_by_default(): void
    {
        $this->assertTrue((new BehaviourTestImporter())->headerToSnakeCase());
    }

    public function test_undefined_columns_are_dropped_by_default(): void
    {
        $this->assertFalse((new BehaviourTestImporter())->mergeWithUndefinedColumns());
    }

    public function test_export_and_download_example_urls_are_null_by_default(): void
    {
        $importer = new BehaviourTestImporter();

        $this->assertNull($importer->getExportUrl());
        $this->assertNull($importer->getDownloadExampleUrl());
    }

    public function test_examples_are_empty_by_default(): void
    {
        $this->assertSame([], (new BehaviourTestImporter())->getExamples());
    }

    public function test_rules_cheat_sheet_is_shown_only_when_a_column_declares_rules(): void
    {
        $this->assertTrue((new BehaviourTestImporter())->showRulesCheatSheet());
        $this->assertFalse((new NoRulesImporter())->showRulesCheatSheet());
    }

    public function test_get_columns_returns_the_declared_columns(): void
    {
        $names = array_map(
            fn (ImportColumn $column) => $column->getName(),
            (new BehaviourTestImporter())->getColumns()
        );

        $this->assertSame(['name', 'email'], $names);
    }

    public function test_validation_rules_are_keyed_for_a_list_of_rows(): void
    {
        $rules = (new BehaviourTestImporter())->getValidationRules();

        $this->assertArrayHasKey('*.name', $rules);
        $this->assertSame(['required', 'string'], $rules['*.name']);
    }

    public function test_upload_url_points_at_the_package_route(): void
    {
        $this->assertStringContainsString('data-synchronize/upload', (new BehaviourTestImporter())->getUploadUrl());
    }
}

class BehaviourTestImporter extends Importer
{
    public function columns(): array
    {
        return [
            ImportColumn::make('name')->rules(['required', 'string']),
            ImportColumn::make('email')->rules(['nullable', 'email']),
        ];
    }

    public function getValidateUrl(): string
    {
        return '';
    }

    public function getImportUrl(): string
    {
        return '';
    }

    public function handle(array $data): int
    {
        return 0;
    }
}

class NoRulesImporter extends BehaviourTestImporter
{
    public function columns(): array
    {
        return [ImportColumn::make('name')];
    }
}
