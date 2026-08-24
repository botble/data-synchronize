<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Concerns\Importer\HasImportResults;
use PHPUnit\Framework\TestCase;

class HasImportResultsTest extends TestCase
{
    protected object $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new class () {
            use HasImportResults;
        };
    }

    public function test_results_start_empty(): void
    {
        $this->assertCount(0, $this->subject->successes());
        $this->assertCount(0, $this->subject->failures());
    }

    public function test_successes_are_collected_in_order(): void
    {
        $this->subject->onSuccess(['id' => 1]);
        $this->subject->onSuccess(['id' => 2]);

        $this->assertSame([['id' => 1], ['id' => 2]], $this->subject->successes()->all());
    }

    public function test_a_failure_records_row_attribute_errors_and_values(): void
    {
        $this->subject->onFailure(3, 'name', ['The name field is required.'], ['name' => '']);

        $this->assertSame([[
            'row' => 3,
            'attribute' => 'name',
            'errors' => ['The name field is required.'],
            'values' => ['name' => ''],
        ]], $this->subject->failures()->all());
    }

    public function test_failure_values_default_to_an_empty_array(): void
    {
        $this->subject->onFailure(1, 'name', ['required']);

        $this->assertSame([], $this->subject->failures()->first()['values']);
    }

    public function test_multiple_failures_on_the_same_row_are_all_kept(): void
    {
        $this->subject->onFailure(2, 'name', ['required']);
        $this->subject->onFailure(2, 'price', ['numeric']);

        $this->assertCount(2, $this->subject->failures());
        $this->assertSame([2, 2], $this->subject->failures()->pluck('row')->all());
        $this->assertSame(['name', 'price'], $this->subject->failures()->pluck('attribute')->all());
    }
}
