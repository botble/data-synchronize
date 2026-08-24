<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClearChunksCommandTest extends TestCase
{
    protected string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
    }

    public function test_it_reports_when_there_is_nothing_to_clear(): void
    {
        $this->artisan('cms:data-synchronize:clear-chunks')
            ->expectsOutputToContain('No expired chunk files found')
            ->assertExitCode(0);
    }

    public function test_it_removes_leftover_chunk_files(): void
    {
        Storage::disk('local')->put("{$this->storagePath}/a.csv", 'one');
        Storage::disk('local')->put("{$this->storagePath}/b.csv", 'two');

        $this->artisan('cms:data-synchronize:clear-chunks')
            ->expectsOutputToContain('2 expired chunk files removed')
            ->assertExitCode(0);

        $this->assertSame([], Storage::disk('local')->allFiles($this->storagePath));
    }

    public function test_it_also_clears_nested_chunk_directories(): void
    {
        Storage::disk('local')->put("{$this->storagePath}/nested/part.csv", 'one');

        $this->artisan('cms:data-synchronize:clear-chunks')->assertExitCode(0);

        $this->assertSame([], Storage::disk('local')->allFiles($this->storagePath));
    }

    public function test_it_leaves_files_outside_the_import_folder_alone(): void
    {
        Storage::disk('local')->put('unrelated/keep.txt', 'keep me');
        Storage::disk('local')->put("{$this->storagePath}/a.csv", 'one');

        $this->artisan('cms:data-synchronize:clear-chunks')->assertExitCode(0);

        $this->assertTrue(Storage::disk('local')->exists('unrelated/keep.txt'));
    }
}
