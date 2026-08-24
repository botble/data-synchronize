<?php

namespace Botble\DataSynchronize\Tests\Unit;

use Botble\DataSynchronize\Importer\Importer;
use ReflectionMethod;
use Tests\TestCase;

class ImporterFilePathTest extends TestCase
{
    /**
     * The file name arrives straight from the request, so whatever a caller passes
     * must always resolve to a file inside the configured import folder.
     */
    public function test_resolve_file_path_keeps_the_file_inside_the_import_folder(): void
    {
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

        $cases = [
            'products.csv' => "$storagePath/products.csv",
            '../products.csv' => "$storagePath/products.csv",
            '../../../../etc/passwd' => "$storagePath/passwd",
            'nested/dir/products.csv' => "$storagePath/products.csv",
            'sub/../../outside.csv' => "$storagePath/outside.csv",
        ];

        foreach ($cases as $input => $expected) {
            $this->assertSame($expected, $this->resolve($input), "Failed resolving [$input]");
        }
    }

    public function test_resolved_path_never_contains_a_traversal_segment(): void
    {
        foreach (['../../secret.csv', 'a/../../b.csv', '..\\..\\windows.csv'] as $input) {
            $this->assertStringNotContainsString('..', $this->resolve($input));
        }
    }

    protected function resolve(string $fileName): string
    {
        $importer = new class () extends Importer {
            public function columns(): array
            {
                return [];
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
                return 0;
            }
        };

        $method = new ReflectionMethod(Importer::class, 'resolveFilePath');

        return $method->invoke($importer, $fileName);
    }
}
