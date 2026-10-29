<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Exporter\Exporter;
use PHPUnit\Framework\TestCase;

/**
 * Exports only ever raise memory_limit: a server configured with more memory (or -1) must not be
 * cut down to the export default of 512M.
 */
class ExporterRaiseMemoryLimitTest extends TestCase
{
    protected string $originalLimit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalLimit = (string) ini_get('memory_limit');
    }

    protected function tearDown(): void
    {
        ini_set('memory_limit', $this->originalLimit);

        parent::tearDown();
    }

    public function test_it_does_not_lower_a_higher_limit(): void
    {
        ini_set('memory_limit', '2G');

        Exporter::raiseMemoryLimit('512M');

        $this->assertSame('2G', ini_get('memory_limit'));
    }

    public function test_it_keeps_an_unlimited_limit(): void
    {
        ini_set('memory_limit', '-1');

        Exporter::raiseMemoryLimit('512M');

        $this->assertSame('-1', ini_get('memory_limit'));
    }

    public function test_it_raises_a_lower_limit(): void
    {
        ini_set('memory_limit', (string) max(memory_get_usage(true) + 64 * 1024 * 1024, 128 * 1024 * 1024));

        Exporter::raiseMemoryLimit('4G');

        $this->assertSame('4G', ini_get('memory_limit'));
    }
}
