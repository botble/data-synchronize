<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\DataSynchronize\Http\Requests\ExportRequest;
use Generator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ExportControllerStreamingTest extends TestCase
{
    protected function export(Exporter $exporter, array $input): mixed
    {
        $request = ExportRequest::create('/export', 'POST', $input);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        app()->instance('request', $request);

        return (new StreamingProbeController($exporter))->store($request);
    }

    protected function body(StreamedResponse $response): string
    {
        ob_start();

        try {
            $response->sendContent();
        } finally {
            $content = ob_get_clean();
        }

        return $content;
    }

    protected function rows(StreamedResponse $response): array
    {
        $csv = preg_replace('/^\xEF\xBB\xBF/', '', $this->body($response));

        return array_values(array_filter(explode("\n", trim($csv))));
    }

    public function test_streaming_writes_the_rows_the_exporter_yields(): void
    {
        $response = $this->export(new StreamingProbeExporter(), ['format' => 'csv', 'use_streaming' => 1]);

        $this->assertInstanceOf(StreamedResponse::class, $response);

        $rows = $this->rows($response);

        $this->assertSame('ID,"Full Name",Active', $rows[0]);
        $this->assertSame('1,Ann,1', $rows[1]);
        $this->assertSame('2,Bob,', $rows[2]);
        $this->assertCount(3, $rows);
    }

    public function test_streaming_output_starts_with_a_utf8_bom(): void
    {
        $body = $this->body($this->export(new StreamingProbeExporter(), ['format' => 'csv', 'use_streaming' => 1]));

        $this->assertStringStartsWith(chr(0xEF) . chr(0xBB) . chr(0xBF), $body);
    }

    public function test_streaming_only_writes_the_selected_columns(): void
    {
        $response = $this->export(new StreamingProbeExporter(), [
            'format' => 'csv',
            'use_streaming' => 1,
            'columns' => ['name'],
        ]);

        $rows = $this->rows($response);

        $this->assertSame('"Full Name"', $rows[0]);
        $this->assertSame('Ann', $rows[1]);
    }

    public function test_streaming_flattens_awkward_values(): void
    {
        $rows = $this->rows($this->export(new AwkwardValueExporter(), ['format' => 'csv', 'use_streaming' => 1]));

        // null and false become empty, true becomes 1, arrays and Arrayables join on commas.
        $this->assertSame(',,1,"a,b","c,d","2026-08-24 10:00:00"', $rows[1]);
    }

    public function test_streaming_is_skipped_for_spreadsheet_formats(): void
    {
        $response = $this->export(new StreamingProbeExporter(), ['format' => 'xlsx', 'use_streaming' => 1]);

        $this->assertNotInstanceOf(StreamedResponse::class, $response);
    }

    public function test_streaming_is_skipped_when_the_exporter_cannot_stream(): void
    {
        $response = $this->export(new NonStreamingExporter(), ['format' => 'csv', 'use_streaming' => 1]);

        $this->assertNotInstanceOf(StreamedResponse::class, $response);
    }

    public function test_streaming_turns_on_by_itself_for_large_datasets(): void
    {
        $response = $this->export(new LargeExporter(), ['format' => 'csv']);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_large_dataset_detection_does_not_depend_on_the_counter_language(): void
    {
        // The label used to have to contain the word "total" - which only holds in English.
        $response = $this->export(new LargeExporterWithTranslatedCounter(), ['format' => 'csv']);

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_large_spreadsheet_exports_are_refused_whatever_the_counter_language(): void
    {
        $response = $this->export(new LargeExporterWithTranslatedCounter(), ['format' => 'xlsx']);

        $this->assertInstanceOf(BaseHttpResponse::class, $response);
        $this->assertTrue($response->isError());
        $this->assertStringContainsString('10,001', $response->getMessage());
    }
}

class StreamingProbeController extends ExportController
{
    public function __construct(protected Exporter $probeExporter)
    {
    }

    protected function getExporter(): Exporter
    {
        return $this->probeExporter;
    }
}

class StreamingProbeExporter extends Exporter
{
    public function columns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name')->label('Full Name'),
            ExportColumn::make('active')->label('Active'),
        ];
    }

    public function collection(): Collection
    {
        return collect();
    }

    public function enableStreamingMode(bool $enable = true): self
    {
        return $this;
    }

    public function isStreamingMode(): bool
    {
        return true;
    }

    public function streamingGenerator(): Generator
    {
        yield ['id' => 1, 'name' => 'Ann', 'active' => true];
        yield ['id' => 2, 'name' => 'Bob', 'active' => false];
    }
}

class AwkwardValueExporter extends StreamingProbeExporter
{
    public function columns(): array
    {
        return array_map(
            fn (string $name) => ExportColumn::make($name),
            ['nothing', 'no', 'yes', 'list', 'arrayable', 'when']
        );
    }

    public function streamingGenerator(): Generator
    {
        yield [
            'nothing' => null,
            'no' => false,
            'yes' => true,
            'list' => ['a', 'b'],
            'arrayable' => collect(['c', 'd']),
            'when' => new \DateTimeImmutable('2026-08-24 10:00:00'),
        ];
    }
}

class NonStreamingExporter extends Exporter
{
    public function columns(): array
    {
        return [ExportColumn::make('id')];
    }

    public function collection(): Collection
    {
        return collect();
    }
}

class LargeExporter extends StreamingProbeExporter
{
    public function isStreamingMode(): bool
    {
        return $this->streaming;
    }

    protected bool $streaming = false;

    public function enableStreamingMode(bool $enable = true): self
    {
        $this->streaming = $enable;

        return $this;
    }

    public function counters(): array
    {
        return [ExportCounter::make()->label('Total records')->value('10,001')];
    }
}

class LargeExporterWithTranslatedCounter extends LargeExporter
{
    public function counters(): array
    {
        return [
            ExportCounter::make()->label('Tổng số địa điểm')->value('10,001'),
            ExportCounter::make()->label('Số quốc gia')->value('12'),
        ];
    }
}
