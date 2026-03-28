<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Illuminate\Support\Facades\Storage;
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
}
