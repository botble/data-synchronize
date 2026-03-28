<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Contracts\Importer\WithMapping;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use PHPUnit\Framework\TestCase;

class ImporterTest extends TestCase
{
    public function test_transform_rows_maps_columns_by_heading(): void
    {
        $importer = $this->createImporter([
            ImportColumn::make('name')->heading('Name'),
            ImportColumn::make('email')->heading('Email'),
        ]);

        $result = $importer->transformRows([
            ['Name' => 'John', 'Email' => 'john@example.com'],
        ]);

        $this->assertSame([
            ['name' => 'John', 'email' => 'john@example.com'],
        ], $result);
    }

    public function test_transform_rows_nullable_column_converts_empty_to_null(): void
    {
        $importer = $this->createImporter([
            ImportColumn::make('name'),
            ImportColumn::make('phone')->nullable(),
        ]);

        $result = $importer->transformRows([
            ['name' => 'John', 'phone' => ''],
        ]);

        $this->assertNull($result[0]['phone']);
        $this->assertSame('John', $result[0]['name']);
    }

    public function test_transform_rows_boolean_column_converts_values(): void
    {
        $importer = $this->createImporter([
            ImportColumn::make('name'),
            ImportColumn::make('active')->boolean('Yes', 'No'),
        ]);

        $result = $importer->transformRows([
            ['name' => 'John', 'active' => 'Yes'],
            ['name' => 'Jane', 'active' => 'No'],
        ]);

        $this->assertSame(1, $result[0]['active']);
        $this->assertSame(0, $result[1]['active']);
    }

    public function test_transform_rows_with_mapping_applies_map(): void
    {
        $importer = $this->createImporterWithMapping(
            [
                ImportColumn::make('value')
                    ->rules(['nullable', 'string']),
            ],
            function (array $row) {
                if (isset($row['value']) && ! is_string($row['value'])) {
                    $row['value'] = (string) $row['value'];
                }

                return $row;
            }
        );

        $result = $importer->transformRows([
            ['value' => 404],
            ['value' => 'text'],
        ]);

        $this->assertSame('404', $result[0]['value']);
        $this->assertSame('text', $result[1]['value']);
    }

    public function test_transform_rows_with_mapping_handles_null_values(): void
    {
        $importer = $this->createImporterWithMapping(
            [
                ImportColumn::make('value')->nullable(),
            ],
            function (array $row) {
                if (isset($row['value']) && ! is_string($row['value'])) {
                    $row['value'] = (string) $row['value'];
                }

                return $row;
            }
        );

        $result = $importer->transformRows([
            ['value' => null],
            ['value' => ''],
        ]);

        $this->assertNull($result[0]['value']);
        $this->assertNull($result[1]['value']);
    }

    public function test_get_validation_rules_uses_column_rules(): void
    {
        $importer = $this->createImporter([
            ImportColumn::make('name')->rules(['required', 'string']),
            ImportColumn::make('email')->rules(['required', 'email']),
        ]);

        $rules = $importer->getValidationRules();

        $this->assertSame(['required', 'string'], $rules['*.name']);
        $this->assertSame(['required', 'email'], $rules['*.email']);
    }

    public function test_get_validation_rules_empty_when_no_rules(): void
    {
        $importer = $this->createImporter([
            ImportColumn::make('name'),
        ]);

        $rules = $importer->getValidationRules();

        $this->assertSame([], $rules['*.name']);
    }

    public function test_header_to_snake_case_default_true(): void
    {
        $importer = $this->createImporter([]);

        $this->assertTrue($importer->headerToSnakeCase());
    }

    public function test_merge_with_undefined_columns_default_false(): void
    {
        $importer = $this->createImporter([]);

        $this->assertFalse($importer->mergeWithUndefinedColumns());
    }

    public function test_get_export_url_default_null(): void
    {
        $importer = $this->createImporter([]);

        $this->assertNull($importer->getExportUrl());
    }

    /**
     * Creates a test importer that bypasses apply_filters() by overriding
     * getColumns() and getValidationRules() to return columns directly.
     */
    protected function createImporter(array $columns): Importer
    {
        return new class ($columns) extends Importer
        {
            public function __construct(protected array $importColumns)
            {
            }

            public function columns(): array
            {
                return $this->importColumns;
            }

            public function getColumns(): array
            {
                return $this->columns();
            }

            public function getValidationRules(): array
            {
                return collect($this->getColumns())
                    ->mapWithKeys(fn (ImportColumn $column) => ["*.{$column->getName()}" => $column->getRules()])
                    ->all();
            }

            public function getValidateUrl(): string
            {
                return '/validate';
            }

            public function getImportUrl(): string
            {
                return '/import';
            }

            public function handle(array $data): int
            {
                return count($data);
            }
        };
    }

    protected function createImporterWithMapping(array $columns, callable $mapFn): Importer
    {
        return new class ($columns, $mapFn) extends Importer implements WithMapping
        {
            public function __construct(
                protected array $importColumns,
                protected $mapFn,
            ) {
            }

            public function columns(): array
            {
                return $this->importColumns;
            }

            public function getColumns(): array
            {
                return $this->columns();
            }

            public function getValidationRules(): array
            {
                return collect($this->getColumns())
                    ->mapWithKeys(fn (ImportColumn $column) => ["*.{$column->getName()}" => $column->getRules()])
                    ->all();
            }

            public function getValidateUrl(): string
            {
                return '/validate';
            }

            public function getImportUrl(): string
            {
                return '/import';
            }

            public function handle(array $data): int
            {
                return count($data);
            }

            public function map(mixed $row): array
            {
                return ($this->mapFn)($row);
            }
        };
    }
}
