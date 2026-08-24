<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImporterChunkingTest extends TestCase
{
    protected string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
    }

    protected function putCsv(string $name, array $rows, array $headings = ['name', 'email']): void
    {
        $lines = [implode(',', $headings)];

        foreach ($rows as $row) {
            $lines[] = implode(',', array_map(fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"', $row));
        }

        Storage::disk('local')->put("{$this->storagePath}/{$name}", implode("\n", $lines) . "\n");
    }

    protected function importer(): ChunkTestImporter
    {
        return new ChunkTestImporter();
    }

    public function test_get_rows_reads_every_row_with_snake_cased_headings(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']], ['Name', 'Email']);

        $rows = $this->importer()->getRows('people.csv')->all();

        $this->assertCount(2, $rows);
        $this->assertSame('Ann', $rows[0]['name']);
        $this->assertSame('bob@example.com', $rows[1]['email']);
    }

    public function test_get_rows_honours_offset_and_limit(): void
    {
        $this->putCsv('people.csv', [['A', 'a@x.com'], ['B', 'b@x.com'], ['C', 'c@x.com'], ['D', 'd@x.com']]);

        $rows = array_values($this->importer()->getRows('people.csv', 1, 2)->all());

        $this->assertCount(2, $rows);
        $this->assertSame('B', $rows[0]['name']);
        $this->assertSame('C', $rows[1]['name']);
    }

    public function test_get_rows_by_offset_returns_a_plain_array(): void
    {
        $this->putCsv('people.csv', [['A', 'a@x.com']]);

        $this->assertIsArray($this->importer()->getRowsByOffset('people.csv', 0, 10));
    }

    public function test_get_rows_throws_when_the_file_is_missing(): void
    {
        $this->expectExceptionMessage('File not found at path');

        $this->importer()->getRows('does-not-exist.csv')->all();
    }

    public function test_validate_reports_the_total_and_no_errors_for_good_data(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']]);

        $response = $this->importer()->validate('people.csv', 0, 10);

        $this->assertSame(2, $response->total);
        $this->assertSame(2, $response->count);
        $this->assertSame([], $response->errors);
        $this->assertSame('people.csv', $response->fileName);
    }

    public function test_validate_reports_column_rule_violations(): void
    {
        $this->putCsv('people.csv', [['', 'not-an-email']]);

        $errors = $this->importer()->validate('people.csv', 0, 10)->errors;

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('name', strtolower(implode(' ', $errors)));
    }

    public function test_validate_walks_the_file_one_chunk_at_a_time(): void
    {
        $this->putCsv('people.csv', [['A', 'a@x.com'], ['B', 'b@x.com'], ['C', 'c@x.com']]);

        $importer = $this->importer();

        $first = $importer->validate('people.csv', 0, 2);
        $second = $importer->validate('people.csv', $first->getNextOffset(), 2);

        $this->assertSame(3, $first->total);
        $this->assertSame(2, $first->getNextOffset());
        $this->assertSame(1, $second->count);
        $this->assertSame(3, $second->getNextOffset());
    }

    public function test_validate_renames_the_file_once_the_offset_runs_past_the_end(): void
    {
        $this->putCsv('people.csv', [['A', 'a@x.com']]);

        $response = $this->importer()->validate('people.csv', 10, 10);

        $this->assertSame(0, $response->count);
        $this->assertNotSame('people.csv', $response->fileName);
        $this->assertTrue(Storage::disk('local')->exists("{$this->storagePath}/{$response->fileName}"));
        $this->assertFalse(Storage::disk('local')->exists("{$this->storagePath}/people.csv"));
    }

    public function test_import_hands_transformed_rows_to_handle_and_counts_them(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']]);

        $importer = $this->importer();
        $response = $importer->import('people.csv', 0, 10);

        $this->assertSame(2, $response->imported);
        $this->assertSame(2, $response->count);
        $this->assertSame(
            [['name' => 'Ann', 'email' => 'ann@example.com'], ['name' => 'Bob', 'email' => 'bob@example.com']],
            $importer->handled
        );
    }

    public function test_import_skips_rows_already_recorded_as_failures(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']]);

        $importer = $this->importer();
        $importer->onFailure(1, 'name', ['bad row']);

        $response = $importer->import('people.csv', 0, 10);

        $this->assertSame([['name' => 'Bob', 'email' => 'bob@example.com']], $importer->handled);
        $this->assertSame(1, $response->imported);
        $this->assertCount(1, $response->failures);
    }

    public function test_import_deletes_the_working_file_once_the_chunk_is_empty(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com']]);

        $this->importer()->import('people.csv', 10, 10);

        $this->assertFalse(Storage::disk('local')->exists("{$this->storagePath}/people.csv"));
    }
}

class ChunkTestImporter extends Importer
{
    public array $handled = [];

    public function columns(): array
    {
        return [
            ImportColumn::make('name')->rules(['required', 'string']),
            ImportColumn::make('email')->rules(['required', 'email']),
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
        $this->handled = array_values($data);

        return count($data);
    }
}
