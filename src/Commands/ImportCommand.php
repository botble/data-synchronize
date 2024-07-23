<?php

namespace Botble\DataSynchronize\Commands;

use Botble\DataSynchronize\Importer\Importer;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\search;
use function Laravel\Prompts\text;

use SplFileInfo;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

#[AsCommand(name: 'data-synchronize:import', description: 'Import data from Excel/CSV file')]
class ImportCommand extends Command implements PromptsForMissingInput
{
    public function handle(): void
    {
        $importer = $this->argument('importer') ?: search(
            label: 'Which importer do you want to use?',
            options: fn (string $value) => array_filter(
                $this->possibleImporters(),
                fn (string $importer) => str_contains(strtolower($importer), strtolower($value))
            ),
        );
        $path = $this->argument('path') ?: text(
            'Where is the source Excel/CSV file?',
        );
        $limit = (int) $this->option('limit') ?: 100;

        if (! file_exists($path)) {
            $this->components->error('File does not exist');

            exit(self::FAILURE);
        }

        if (! class_exists($importer)) {
            $this->components->error('Importer class does not exist');

            exit(self::FAILURE);
        }

        $importer = new $importer();

        if (! $importer instanceof Importer) {
            $this->components->error('Importer class must be an instance of ' . Importer::class);

            exit(self::FAILURE);
        }

        $basename = basename($path);
        $storage = Storage::disk('local');
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
        $filePath = sprintf('%s/%s', $storagePath, $basename);

        $storage->put($filePath, file_get_contents($path));

        $this->validateData($importer, $basename, $limit);

        $this->importData($importer, $basename, $limit);

        $storage->delete($filePath);

        exit(self::SUCCESS);
    }

    protected function validateData(Importer $importer, string $basename, int $limit = 100): void
    {
        $offset = 0;

        $this->components->info('Validating data...');

        do {
            $response = $importer->validate($basename, $offset, $limit);
            $offset = $response->getNextOffset();

            $this->components->info("Validated data from {$response->getFromOffset()} to {$response->getNextOffset()}");
        } while ($response->getNextOffset() < $response->total);

        $this->components->info('Validated data successfully');
    }

    protected function importData(Importer $importer, string $basename, int $limit = 100): void
    {
        $this->components->info('Importing data...');

        $total = 0;
        $offset = 0;

        do {
            $response = $importer->import($basename, $offset, $limit);
            $offset = $response->getNextOffset();
            $total += $response->imported;

            $from = $response->getFromOffset();
            $to = $response->getNextOffset();

            if ($from > $to) {
                if ($total > 0) {
                    $this->components->info($importer->getDoneMessage($total));
                } else {
                    $this->components->info('Your data is up to date');
                }
            } else {
                $this->components->info("Imported {$importer->getLabel()} from {$from} to {$to}");
            }
        } while ($from <= $to);
    }

    protected function getOptions(): array
    {
        return [
            'limit' => ['limit', null, InputOption::VALUE_OPTIONAL, 'The limit of records to import'],
        ];
    }

    protected function getArguments(): array
    {
        return [
            ['importer', InputArgument::OPTIONAL, 'The exporter class name'],
            ['path', InputArgument::OPTIONAL, 'The path to the source Excel/CSV file'],
        ];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'importer' => ['What is the importer class name?', 'E.g. Botble\Blog\Importers\PostImporter'],
            'path' => ['Where is the source Excel/CSV file?', 'E.g. ~/Downloads/posts.xlsx'],
        ];
    }

    protected function possibleImporters(): array
    {
        $importers = [];

        collect(
            Finder::create()
                ->files()
                ->in(platform_path())
                ->name('*.php')
                ->contains(Importer::class)
        )
            ->map(function (SplFileInfo $file) use (&$importers) {
                $class = $this->resolveExporterNamespace($file->getPathname());

                if (
                    class_exists($class)
                    && is_subclass_of($class, Importer::class)
                ) {
                    $importers[] = $class;
                }
            });

        return array_combine($importers, $importers);
    }

    protected function resolveExporterNamespace(string $path): string
    {
        $content = file_get_contents($path);
        $namespace = str($content)->after('namespace ')->before(';')->trim();
        $basename = basename($path, '.php');

        return $namespace . '\\' . $basename;
    }
}
