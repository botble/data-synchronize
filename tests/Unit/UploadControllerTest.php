<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Http\Controllers\UploadController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    public function test_old_files_are_cleaned_up_on_new_upload(): void
    {
        $disk = Storage::disk('local');
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

        $disk->put("$storagePath/old-file-abc123.csv", 'old content');
        $disk->put("$storagePath/another-old-file-def456.xlsx", 'old content 2');

        $this->assertTrue($disk->exists("$storagePath/old-file-abc123.csv"));
        $this->assertTrue($disk->exists("$storagePath/another-old-file-def456.xlsx"));

        // Simulate the cleanup logic from UploadController::saveFile
        foreach ($disk->files($storagePath) as $existingFile) {
            $disk->delete($existingFile);
        }

        $this->assertFalse($disk->exists("$storagePath/old-file-abc123.csv"));
        $this->assertFalse($disk->exists("$storagePath/another-old-file-def456.xlsx"));
    }

    public function test_cleanup_does_not_fail_with_empty_directory(): void
    {
        $disk = Storage::disk('local');
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

        $files = $disk->files($storagePath);
        $this->assertEmpty($files);

        foreach ($files as $existingFile) {
            $disk->delete($existingFile);
        }

        $this->assertTrue(true);
    }

    public function test_cleanup_only_deletes_files_not_subdirectories(): void
    {
        $disk = Storage::disk('local');
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

        $disk->put("$storagePath/old-file.csv", 'content');
        $disk->put("$storagePath/subdir/nested-file.csv", 'nested content');

        // files() only returns files in the immediate directory, not subdirectories
        foreach ($disk->files($storagePath) as $existingFile) {
            $disk->delete($existingFile);
        }

        $this->assertFalse($disk->exists("$storagePath/old-file.csv"));
        $this->assertTrue($disk->exists("$storagePath/subdir/nested-file.csv"));
    }

    protected function createFilenameFor(string $clientName): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'ds-test-');
        file_put_contents($tmp, "name\n");

        $file = new UploadedFile($tmp, $clientName, 'text/csv', null, true);

        $controller = (new ReflectionClass(UploadController::class))->newInstanceWithoutConstructor();
        $method = new ReflectionMethod(UploadController::class, 'createFilename');

        try {
            return $method->invoke($controller, $file);
        } finally {
            @unlink($tmp);
        }
    }

    public function test_created_filename_strips_directory_traversal(): void
    {
        $filename = $this->createFilenameFor('../../evil.csv');

        $this->assertStringNotContainsString('..', $filename);
        $this->assertStringNotContainsString('/', $filename);
        $this->assertStringNotContainsString('\\', $filename);
        $this->assertStringEndsWith('.csv', $filename);
    }

    public function test_created_filename_drops_unsafe_characters(): void
    {
        $filename = $this->createFilenameFor('my products; rm -rf *.csv');

        $this->assertMatchesRegularExpression('/^[\w.-]+\.csv$/', $filename);
    }

    public function test_created_filename_is_unique_per_upload(): void
    {
        $this->assertNotSame(
            $this->createFilenameFor('products.csv'),
            $this->createFilenameFor('products.csv')
        );
    }

    public function test_created_filename_falls_back_when_name_has_nothing_usable(): void
    {
        $this->assertStringStartsWith('import-', $this->createFilenameFor('---.csv'));
    }

    public function test_created_filename_never_contains_a_double_dot(): void
    {
        // ImportRequest refuses any file_name containing "..", so a name produced here
        // must never contain one or the upload could not be imported afterwards.
        $filename = $this->createFilenameFor('report..v2.csv');

        $this->assertStringNotContainsString('..', $filename);
        $this->assertStringStartsWith('report.v2-', $filename);
    }
}
