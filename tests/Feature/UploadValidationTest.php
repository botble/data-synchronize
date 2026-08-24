<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\DataSynchronize\Http\Controllers\UploadController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

class UploadValidationTest extends TestCase
{
    protected string $storagePath;

    protected string $tmpDir;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
        $this->tmpDir = sys_get_temp_dir() . '/ds-upload-' . uniqid();

        File::ensureDirectoryExists($this->tmpDir);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->tmpDir);

        parent::tearDown();
    }

    /**
     * Mirrors what the chunk receiver hands to saveFile(): a real file on disk
     * carrying the name the browser sent.
     */
    protected function save(string $clientName, string $contents): BaseHttpResponse
    {
        $path = $this->tmpDir . '/' . md5($clientName) . '-source';

        File::put($path, $contents);

        $file = new UploadedFile($path, $clientName, null, null, true);

        $controller = (new ReflectionClass(UploadController::class))->newInstanceWithoutConstructor();

        return (new ReflectionMethod(UploadController::class, 'saveFile'))->invoke($controller, $file);
    }

    public function test_it_accepts_a_csv_upload(): void
    {
        $response = $this->save('products.csv', "name,email\nAnn,ann@example.com\n");

        $this->assertFalse($response->isError());
        $this->assertCount(1, Storage::disk('local')->files($this->storagePath));
    }

    public function test_it_accepts_an_uppercase_extension(): void
    {
        $this->assertFalse($this->save('PRODUCTS.CSV', "name\nAnn\n")->isError());
    }

    public function test_it_rejects_a_php_file_that_looks_like_plain_text(): void
    {
        // The allowed mime list includes text/plain, so only the extension check
        // stops this one.
        $response = $this->save('shell.php', "name,email\nAnn,ann@example.com\n");

        $this->assertTrue($response->isError());
        $this->assertSame([], Storage::disk('local')->files($this->storagePath));
    }

    public function test_it_rejects_a_file_whose_contents_are_not_a_spreadsheet(): void
    {
        $response = $this->save('image.csv', "\x89PNG\r\n\x1a\n" . str_repeat("\x00", 64));

        $this->assertTrue($response->isError());
    }

    public function test_it_stores_the_file_under_a_sanitised_unique_name(): void
    {
        $this->save('../my products.csv', "name\nAnn\n");

        $files = Storage::disk('local')->files($this->storagePath);

        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('..', $files[0]);
        $this->assertStringStartsWith("{$this->storagePath}/my-products-", $files[0]);
        $this->assertStringEndsWith('.csv', $files[0]);
    }

    public function test_a_new_upload_clears_the_previous_one(): void
    {
        Storage::disk('local')->put("{$this->storagePath}/stale.csv", 'old');

        $this->save('products.csv', "name\nAnn\n");

        $files = Storage::disk('local')->files($this->storagePath);

        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('stale.csv', $files[0]);
    }
}
