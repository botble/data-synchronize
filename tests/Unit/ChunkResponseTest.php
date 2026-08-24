<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\DataTransferObjects\ChunkImportResponse;
use Botble\DataSynchronize\DataTransferObjects\ChunkResponse;
use Botble\DataSynchronize\DataTransferObjects\ChunkValidateResponse;
use PHPUnit\Framework\TestCase;

/**
 * The offsets these objects report drive the import/validate loops in both the
 * command and the admin JS, so the arithmetic is worth pinning down exactly.
 */
class ChunkResponseTest extends TestCase
{
    public function test_from_offset_is_one_based(): void
    {
        $this->assertSame(1, (new ChunkResponse(offset: 0, count: 50))->getFromOffset());
        $this->assertSame(51, (new ChunkResponse(offset: 50, count: 50))->getFromOffset());
    }

    public function test_next_offset_is_offset_plus_count(): void
    {
        $this->assertSame(50, (new ChunkResponse(offset: 0, count: 50))->getNextOffset());
        $this->assertSame(100, (new ChunkResponse(offset: 50, count: 50))->getNextOffset());
    }

    public function test_a_short_final_chunk_reports_the_real_end(): void
    {
        $response = new ChunkResponse(offset: 200, count: 7);

        $this->assertSame(201, $response->getFromOffset());
        $this->assertSame(207, $response->getNextOffset());
    }

    public function test_an_empty_chunk_makes_from_offset_exceed_next_offset(): void
    {
        // This is the loop's termination signal: from > to means "nothing left".
        $response = new ChunkResponse(offset: 250, count: 0);

        $this->assertSame(251, $response->getFromOffset());
        $this->assertSame(250, $response->getNextOffset());
        $this->assertGreaterThan($response->getNextOffset(), $response->getFromOffset());
    }

    public function test_validate_response_carries_total_file_name_and_errors(): void
    {
        $response = new ChunkValidateResponse(
            offset: 0,
            count: 50,
            total: 250,
            fileName: 'products.csv',
            errors: ['The 1.name field is required.'],
        );

        $this->assertSame(250, $response->total);
        $this->assertSame('products.csv', $response->fileName);
        $this->assertSame(['The 1.name field is required.'], $response->errors);
        $this->assertSame(1, $response->getFromOffset());
        $this->assertSame(50, $response->getNextOffset());
    }

    public function test_validate_response_defaults_to_no_errors(): void
    {
        $response = new ChunkValidateResponse(offset: 0, count: 1, total: 1, fileName: 'a.csv');

        $this->assertSame([], $response->errors);
    }

    public function test_import_response_carries_imported_count_and_failures(): void
    {
        $failures = [['row' => 2, 'attribute' => 'name', 'errors' => ['required'], 'values' => []]];

        $response = new ChunkImportResponse(offset: 0, count: 50, imported: 49, failures: $failures);

        $this->assertSame(49, $response->imported);
        $this->assertSame($failures, $response->failures);
        $this->assertSame(50, $response->getNextOffset());
    }

    public function test_import_response_defaults_to_no_failures(): void
    {
        $this->assertSame([], (new ChunkImportResponse(offset: 0, count: 1, imported: 1))->failures);
    }
}
