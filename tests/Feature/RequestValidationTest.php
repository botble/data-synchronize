<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\DataSynchronize\Http\Requests\DownloadTemplateRequest;
use Botble\DataSynchronize\Http\Requests\ExportRequest;
use Botble\DataSynchronize\Http\Requests\ImportRequest;
use Tests\TestCase;

class RequestValidationTest extends TestCase
{
    protected function fails(object $request, array $data): bool
    {
        return validator($data, $request->rules())->fails();
    }

    public function test_import_request_accepts_a_plain_file_name(): void
    {
        $this->assertFalse($this->fails(new ImportRequest(), [
            'file_name' => 'products-9f8e7d.csv',
            'offset' => 0,
            'limit' => 50,
        ]));
    }

    public function test_import_request_rejects_path_separators_and_traversal(): void
    {
        foreach (['../products.csv', 'nested/products.csv', '..\\products.csv', 'a/../b.csv', '..'] as $name) {
            $this->assertTrue(
                $this->fails(new ImportRequest(), ['file_name' => $name, 'offset' => 0, 'limit' => 50]),
                "Expected [$name] to be rejected."
            );
        }
    }

    public function test_import_request_requires_offset_and_limit_as_integers(): void
    {
        $this->assertTrue($this->fails(new ImportRequest(), ['file_name' => 'a.csv']));
        $this->assertTrue($this->fails(new ImportRequest(), ['file_name' => 'a.csv', 'offset' => 'x', 'limit' => 50]));
    }

    public function test_import_request_total_is_optional(): void
    {
        $this->assertFalse($this->fails(new ImportRequest(), ['file_name' => 'a.csv', 'offset' => 0, 'limit' => 50]));
        $this->assertFalse($this->fails(new ImportRequest(), [
            'file_name' => 'a.csv', 'offset' => 0, 'limit' => 50, 'total' => 100,
        ]));
    }

    public function test_import_request_caps_the_file_name_length(): void
    {
        $this->assertTrue($this->fails(new ImportRequest(), [
            'file_name' => str_repeat('a', 256) . '.csv',
            'offset' => 0,
            'limit' => 50,
        ]));
    }

    public function test_export_request_requires_a_supported_format(): void
    {
        $this->assertFalse($this->fails(new ExportRequest(), ['format' => 'csv']));
        $this->assertFalse($this->fails(new ExportRequest(), ['format' => 'xlsx']));
        $this->assertTrue($this->fails(new ExportRequest(), ['format' => 'pdf']));
        $this->assertTrue($this->fails(new ExportRequest(), []));
    }

    public function test_export_request_accepts_a_list_of_column_names(): void
    {
        $this->assertFalse($this->fails(new ExportRequest(), ['format' => 'csv', 'columns' => ['id', 'name']]));
        $this->assertTrue($this->fails(new ExportRequest(), ['format' => 'csv', 'columns' => 'id']));
    }

    public function test_export_request_bounds_the_chunk_size(): void
    {
        $this->assertFalse($this->fails(new ExportRequest(), ['format' => 'csv', 'chunk_size' => 50]));
        $this->assertFalse($this->fails(new ExportRequest(), ['format' => 'csv', 'chunk_size' => 5000]));
        $this->assertTrue($this->fails(new ExportRequest(), ['format' => 'csv', 'chunk_size' => 49]));
        $this->assertTrue($this->fails(new ExportRequest(), ['format' => 'csv', 'chunk_size' => 5001]));
    }

    public function test_download_template_request_requires_a_supported_format(): void
    {
        $this->assertFalse($this->fails(new DownloadTemplateRequest(), ['format' => 'xlsx']));
        $this->assertTrue($this->fails(new DownloadTemplateRequest(), ['format' => 'txt']));
        $this->assertTrue($this->fails(new DownloadTemplateRequest(), []));
    }
}
