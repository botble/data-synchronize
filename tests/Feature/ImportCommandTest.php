<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\PendingCommand;
use Tests\TestCase;

class ImportCommandTest extends TestCase
{
    protected string $workDir;

    protected string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        CommandTestImporter::$handled = [];

        $this->storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
        $this->workDir = sys_get_temp_dir() . '/ds-command-' . uniqid();

        File::ensureDirectoryExists($this->workDir);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->workDir);

        parent::tearDown();
    }

    protected function writeCsv(string $relativePath, array $rows): string
    {
        $path = $this->workDir . '/' . $relativePath;

        File::ensureDirectoryExists(dirname($path));

        $lines = ['name,email'];

        foreach ($rows as $row) {
            $lines[] = implode(',', $row);
        }

        File::put($path, implode("\n", $lines) . "\n");

        return $path;
    }

    protected function import(string $path, array $options = []): PendingCommand
    {
        return $this->artisan('data-synchronize:import', [
            'importer' => CommandTestImporter::class,
            'path' => $path,
            ...$options,
        ]);
    }

    public function test_it_fails_when_the_source_file_does_not_exist(): void
    {
        $this->import($this->workDir . '/missing.csv')
            ->expectsOutputToContain('File does not exist')
            ->assertExitCode(1);
    }

    public function test_it_fails_when_the_importer_class_does_not_exist(): void
    {
        $this->artisan('data-synchronize:import', [
            'importer' => 'App\\Nope\\NotAnImporter',
            'path' => $this->writeCsv('a.csv', [['Ann', 'ann@example.com']]),
        ])
            ->expectsOutputToContain('Importer class does not exist')
            ->assertExitCode(1);
    }

    public function test_it_fails_when_the_class_is_not_an_importer(): void
    {
        $this->artisan('data-synchronize:import', [
            'importer' => static::class,
            'path' => $this->writeCsv('a.csv', [['Ann', 'ann@example.com']]),
        ])
            ->expectsOutputToContain('must be an instance of')
            ->assertExitCode(1);
    }

    public function test_it_imports_a_valid_file(): void
    {
        $this->import($this->writeCsv('good.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']]))
            ->assertExitCode(0);

        $this->assertCount(2, CommandTestImporter::$handled);
        $this->assertSame('Ann', CommandTestImporter::$handled[0]['name']);
    }

    public function test_it_refuses_to_import_a_file_that_fails_validation(): void
    {
        $this->import($this->writeCsv('bad.csv', [['', 'ann@example.com']]))
            ->expectsOutputToContain('validation error')
            ->expectsOutputToContain('Nothing was imported')
            ->assertExitCode(1);

        $this->assertSame([], CommandTestImporter::$handled, 'No row may reach handle() when validation failed.');
    }

    public function test_force_imports_a_file_that_fails_validation(): void
    {
        $this->import($this->writeCsv('bad.csv', [['', 'ann@example.com']]), ['--force' => true])
            ->expectsOutputToContain('--force')
            ->assertExitCode(0);

        $this->assertCount(1, CommandTestImporter::$handled);
    }

    public function test_it_removes_its_working_copy_on_success_and_on_failure(): void
    {
        $this->import($this->writeCsv('good.csv', [['Ann', 'ann@example.com']]))->run();
        $this->assertSame([], Storage::disk('local')->files($this->storagePath));

        $this->import($this->writeCsv('bad.csv', [['', 'x@example.com']]))->run();
        $this->assertSame([], Storage::disk('local')->files($this->storagePath));
    }

    public function test_files_sharing_a_name_in_different_folders_do_not_collide(): void
    {
        // Regression: a previous run left storage/app/data-synchronize/chunk.csv behind
        // and the next chunk.csv from another folder was silently imported from that copy.
        $this->import($this->writeCsv('a/chunk.csv', [['FromA', 'a@example.com']]))->run();
        $this->assertSame('FromA', CommandTestImporter::$handled[0]['name']);

        CommandTestImporter::$handled = [];

        $this->import($this->writeCsv('b/chunk.csv', [['FromB', 'b@example.com']]))->run();
        $this->assertSame('FromB', CommandTestImporter::$handled[0]['name']);
    }
}

class CommandTestImporter extends Importer
{
    public static array $handled = [];

    public function columns(): array
    {
        return [
            ImportColumn::make('name')->rules(['required', 'string', 'max:250']),
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
        static::$handled = [...static::$handled, ...array_values($data)];

        return count($data);
    }
}
