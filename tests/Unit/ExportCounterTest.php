<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Exporter\ExportCounter;
use PHPUnit\Framework\TestCase;

class ExportCounterTest extends TestCase
{
    public function test_it_stores_a_label_and_value(): void
    {
        $counter = ExportCounter::make()->label('Total products')->value(1234);

        $this->assertSame('Total products', $counter->getLabel());
        $this->assertSame('1234', $counter->getValue());
    }

    public function test_value_accepts_a_preformatted_string(): void
    {
        $this->assertSame('1,234', ExportCounter::make()->value('1,234')->getValue());
    }

    public function test_builder_methods_are_chainable(): void
    {
        $counter = ExportCounter::make();

        $this->assertSame($counter, $counter->label('Total'));
        $this->assertSame($counter, $counter->value(1));
    }

    public function test_make_returns_a_new_instance_each_time(): void
    {
        $this->assertNotSame(ExportCounter::make(), ExportCounter::make());
    }
}
