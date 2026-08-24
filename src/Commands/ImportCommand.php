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
    /**
     * How many validation errors to print before summarising the rest.
     */
    protected const MAX_REPORTED_ERRORS = 20;

    public function handle(): int
    {
        // A large import legitimately runs for hours. The CLI SAPI does not always mean
        // "no time limit" - LiteSpeed's lsphp binary enforces php.ini's max_execution_time
        // even on the command line - so lift it explicitly instead of dying mid-file.
        @set_time_limit(0);

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

            return self::FAILURE;
        }

        if (! class_exists($importer)) {
            $this->components->error('Importer class does not exist');

            return self::FAILURE;
        }

        // Check the type before instantiating - constructing an arbitrary class first
        // turns a wrong class name into a fatal instead of the message below.
        if (! is_subclass_of($importer, Importer::class)) {
            $this->components->error('Importer class must be an instance of ' . Importer::class);

            return self::FAILURE;
        }

        $importer = new $importer();

        // Give the working copy a unique name. Importing several files that share a
        // basename (chunk_0001.csv from two different folders, for example) would
        // otherwise collide here, and a leftover copy could be read instead of this file.
        $basename = sprintf('%s-%s', uniqid(), basename($path));
        $storage = Storage::disk('local');
        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');
        $filePath = sprintf('%s/%s', $storagePath, $basename);

        $storage->put($filePath, file_get_contents($path));

        // Never import a copy we did not actually write - a failed or partial write
        // must stop the run, not silently import whatever is sitting at that path.
        if (! $storage->exists($filePath) || $storage->size($filePath) !== filesize($path)) {
            $this->components->error(sprintf('Could not write a working copy of the file to [%s].', $storagePath));

            $storage->delete($filePath);

            return self::FAILURE;
        }

        if (! $this->validateData($importer, $basename, $limit)) {
            $storage->delete($filePath);

            return self::FAILURE;
        }

        $this->importData($importer, $basename, $limit);

        $storage->delete($filePath);

        return self::SUCCESS;
    }

    protected function validateData(Importer $importer, string $basename, int $limit = 100): bool
    {
        $offset = 0;
        $errors = [];

        $this->components->info('Validating data...');

        do {
            $response = $importer->validate($basename, $offset, $limit);
            $offset = $response->getNextOffset();

            $errors = [...$errors, ...$response->errors];

            $this->components->info("Validated data from {$response->getFromOffset()} to {$response->getNextOffset()}");
        } while ($response->getNextOffset() < $response->total);

        if (! $errors) {
            $this->components->info('Validated data successfully');

            return true;
        }

        $this->components->error(sprintf('Found %s validation error(s) in this file:', number_format(count($errors))));

        foreach (array_slice($errors, 0, static::MAX_REPORTED_ERRORS) as $error) {
            $this->components->warn(sprintf('  - %s', $error));
        }

        if (($remaining = count($errors) - static::MAX_REPORTED_ERRORS) > 0) {
            $this->components->warn(sprintf('  ... and %s more.', number_format($remaining)));
        }

        if ($this->option('force')) {
            $this->components->warn('Continuing anyway because --force was used. Invalid rows may fail to import.');

            return true;
        }

        $this->components->error('Nothing was imported. Fix the file and run again, or pass --force to import it as-is.');

        return false;
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
            'force' => ['force', null, InputOption::VALUE_NONE, 'Import even if the file has validation errors'],
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
