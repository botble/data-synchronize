<?php

namespace Botble\DataSynchronize\Tests\Feature;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\DataSynchronize\Http\Controllers\ImportController;
use Botble\DataSynchronize\Http\Requests\DownloadTemplateRequest;
use Botble\DataSynchronize\Http\Requests\ImportRequest;
use Botble\DataSynchronize\Importer\ImportColumn;
use Botble\DataSynchronize\Importer\Importer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{
    protected string $storagePath;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

    }

    protected function putCsv(string $name, array $rows): void
    {
        $lines = ['name,email'];

        foreach ($rows as $row) {
            $lines[] = implode(',', $row);
        }

        Storage::disk('local')->put("{$this->storagePath}/{$name}", implode("\n", $lines) . "\n");
    }

    protected function controller(?Importer $importer = null): ImportProbeController
    {
        return new ImportProbeController($importer ?? new ControllerTestImporter());
    }

    protected function importRequest(array $input): ImportRequest
    {
        $request = ImportRequest::create('/import', 'POST', $input);
        $request->headers->set('Accept', 'application/json');
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        app()->instance('request', $request);

        return $request;
    }

    /**
     * Render the response the way the admin JS receives it.
     */
    protected function payload(BaseHttpResponse $response): array
    {
        return json_decode($response->toResponse(request())->getContent(), true)['data'];
    }

    public function test_validate_action_reports_totals_and_no_errors_for_good_data(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com'], ['Bob', 'bob@example.com']]);

        $response = $this->controller()->validateData(
            $this->importRequest(['file_name' => 'people.csv', 'offset' => 0, 'limit' => 10])
        );

        $data = $this->payload($response);

        $this->assertFalse($response->isError());
        $this->assertSame(2, $data['total']);
        $this->assertSame(2, $data['count']);
        $this->assertSame([], $data['errors']);
        $this->assertSame('people.csv', $data['file_name']);
    }

    public function test_validate_action_returns_the_rule_violations(): void
    {
        $this->putCsv('people.csv', [['', 'nope']]);

        $response = $this->controller()->validateData(
            $this->importRequest(['file_name' => 'people.csv', 'offset' => 0, 'limit' => 10])
        );

        $this->assertNotEmpty($this->payload($response)['errors']);
    }

    public function test_validate_action_turns_a_missing_file_into_an_error_response(): void
    {
        $response = $this->controller()->validateData(
            $this->importRequest(['file_name' => 'missing.csv', 'offset' => 0, 'limit' => 10])
        );

        $this->assertTrue($response->isError());
        $this->assertStringContainsString('File not found', $response->getMessage());
    }

    public function test_import_action_imports_the_chunk(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com']]);

        $importer = new ControllerTestImporter();

        $response = $this->controller($importer)->import(
            $this->importRequest(['file_name' => 'people.csv', 'offset' => 0, 'limit' => 10])
        );

        $this->assertFalse($response->isError());
        $this->assertSame(1, $this->payload($response)['total']);
        $this->assertSame([['name' => 'Ann', 'email' => 'ann@example.com']], $importer->handled);
    }

    public function test_import_action_accumulates_the_running_total(): void
    {
        $this->putCsv('people.csv', [['Ann', 'ann@example.com']]);

        $response = $this->controller()->import(
            $this->importRequest(['file_name' => 'people.csv', 'offset' => 0, 'limit' => 10, 'total' => 40])
        );

        $this->assertSame(41, $this->payload($response)['total']);
    }

    public function test_import_action_is_blocked_in_demo_mode(): void
    {
        config()->set('core.base.general.demo_mode_enabled', true);

        $this->putCsv('people.csv', [['Ann', 'ann@example.com']]);

        $importer = new ControllerTestImporter();

        $response = $this->controller($importer)->import(
            $this->importRequest(['file_name' => 'people.csv', 'offset' => 0, 'limit' => 10])
        );

        $this->assertTrue($response->isError());
        $this->assertSame([], $importer->handled);
    }

    public function test_download_example_is_blocked_in_demo_mode(): void
    {
        config()->set('core.base.general.demo_mode_enabled', true);

        $response = $this->controller()->downloadExample($this->templateRequest('csv'));

        $this->assertInstanceOf(BaseHttpResponse::class, $response);
        $this->assertTrue($response->isError());
    }

    public function test_download_example_returns_a_template_file(): void
    {
        $response = $this->controller()->downloadExample($this->templateRequest('csv'));

        $this->assertInstanceOf(BinaryFileResponse::class, $response);
    }

    protected function templateRequest(string $format): DownloadTemplateRequest
    {
        $request = DownloadTemplateRequest::create('/download', 'GET', ['format' => $format]);
        $request->setContainer(app())->setRedirector(app('redirect'));
        $request->validateResolved();

        app()->instance('request', $request);

        return $request;
    }
}

class ImportProbeController extends ImportController
{
    public function __construct(protected Importer $probeImporter)
    {
    }

    protected function getImporter(): Importer
    {
        return $this->probeImporter;
    }
}

class ControllerTestImporter extends Importer
{
    public array $handled = [];

    public function columns(): array
    {
        return [
            ImportColumn::make('name')->rules(['required', 'string']),
            ImportColumn::make('email')->rules(['nullable', 'email']),
        ];
    }

    public function getValidateUrl(): string
    {
        return '';
    }

    public function getImportUrl(): string
    {
        return '';
    }

    public function handle(array $data): int
    {
        $this->handled = array_values($data);

        return count($data);
    }
}
