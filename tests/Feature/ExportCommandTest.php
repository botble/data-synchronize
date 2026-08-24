<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\Exporter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExportCommandTest extends TestCase
{
    protected string $outputDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->outputDir = sys_get_temp_dir() . '/ds-export-' . uniqid();

        File::ensureDirectoryExists($this->outputDir);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->outputDir);

        parent::tearDown();
    }

    public function test_it_fails_when_the_exporter_class_does_not_exist(): void
    {
        $this->artisan('data-synchronize:export', [
            'exporter' => 'App\\Nope\\NotAnExporter',
            'path' => $this->outputDir,
        ])
            ->expectsOutputToContain('Exporter class does not exist')
            ->assertExitCode(1);
    }

    public function test_it_fails_when_the_class_is_not_an_exporter(): void
    {
        $this->artisan('data-synchronize:export', [
            'exporter' => static::class,
            'path' => $this->outputDir,
        ])
            ->expectsOutputToContain('must be an instance of')
            ->assertExitCode(1);
    }

    public function test_it_writes_a_csv_file(): void
    {
        $this->artisan('data-synchronize:export', [
            'exporter' => CommandExportSample::class,
            'path' => $this->outputDir,
            '--format' => 'csv',
        ])->assertExitCode(0);

        $files = File::files($this->outputDir);

        $this->assertCount(1, $files);
        $this->assertSame('csv', $files[0]->getExtension());

        $contents = File::get($files[0]->getPathname());

        $this->assertStringContainsString('Ann', $contents);
        $this->assertStringContainsString('ID', $contents);
    }

    public function test_it_writes_a_spreadsheet_file(): void
    {
        $this->artisan('data-synchronize:export', [
            'exporter' => CommandExportSample::class,
            'path' => $this->outputDir,
            '--format' => 'xlsx',
        ])->assertExitCode(0);

        $files = File::files($this->outputDir);

        $this->assertCount(1, $files);
        $this->assertSame('xlsx', $files[0]->getExtension());
    }

    public function test_it_rejects_an_unsupported_format(): void
    {
        $this->artisan('data-synchronize:export', [
            'exporter' => CommandExportSample::class,
            'path' => $this->outputDir,
            '--format' => 'xls',
        ])
            ->expectsOutputToContain('Unsupported format')
            ->assertExitCode(1);

        $this->assertCount(0, File::files($this->outputDir));
    }
}

class CommandExportSample extends Exporter
{
    public function columns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name')->label('Name'),
        ];
    }

    public function collection(): Collection
    {
        return collect([
            (object) ['id' => 1, 'name' => 'Ann'],
            (object) ['id' => 2, 'name' => 'Bob'],
        ]);
    }
}
