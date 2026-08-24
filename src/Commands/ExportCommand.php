<?php

namespace Botble\DataSynchronize\Commands;

use Botble\DataSynchronize\Exporter\Exporter;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\search;
use function Laravel\Prompts\select;

use function Laravel\Prompts\text;

use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

#[AsCommand(name: 'data-synchronize:export', description: 'Export data from database to Excel/Csv file')]
class ExportCommand extends Command implements PromptsForMissingInput
{
    public function handle(): int
    {
        $exporter = $this->argument('exporter') ?: search(
            label: 'Which exporter do you want to use?',
            options: fn (string $value) => array_filter(
                $this->possibleExporters(),
                fn (string $exporter) => str_contains(strtolower($exporter), strtolower($value))
            ),
        );
        $path = $this->argument('path') ?: text(
            'Where do you want to save the file?',
            'E.g. storage/app/exports',
        );
        $format = $this->option('format') ?: select(
            label: 'Which format do you want to export?',
            options: ['csv' => 'CSV', 'xlsx' => 'XLSX'],
            default: 'csv',
        );

        // Only these two reach a writer; anything else used to surface as an
        // UnhandledMatchError from deep inside the exporter.
        if (! in_array($format, ['csv', 'xlsx'], true)) {
            $this->components->error(sprintf('Unsupported format [%s]. Use csv or xlsx.', $format));

            return self::FAILURE;
        }

        if (! class_exists($exporter)) {
            $this->components->error('Exporter class does not exist');

            return self::FAILURE;
        }

        // Check the type before instantiating - constructing an arbitrary class first
        // turns a wrong class name into a fatal instead of the message below.
        if (! is_subclass_of($exporter, Exporter::class)) {
            $this->components->error('Exporter class must be an instance of ' . Exporter::class);

            return self::FAILURE;
        }

        $exporter = new $exporter();

        $exporter->format($format);

        // Configure memory optimization
        if ($this->option('optimize-memory')) {
            $exporter->setOptimizeMemory(true);
        }

        // Configure chunk size if provided
        if ($chunkSize = $this->option('chunk-size')) {
            if (method_exists($exporter, 'setChunkSize')) {
                $exporter->setChunkSize((int) $chunkSize);
            }
        }

        // Show export info
        $this->components->info("Exporting {$exporter->getLabel()} to <comment>{$path}</comment>");

        if (method_exists($exporter, 'getChunkSize')) {
            $this->components->info("Using chunk size: {$exporter->getChunkSize()}");
        }

        try {
            $exporter->export()
                ->getFile()
                ->move($path, $exporter->getExportFileName());
        } catch (Exception $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        $this->components->info(
            "{$exporter->getLabel()} has been exported to <comment>{$path}/{$exporter->getExportFileName()}</comment>"
        );

        return self::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['format', null, InputOption::VALUE_OPTIONAL, 'The format of the file (csv, xlsx)'],
            ['chunk-size', null, InputOption::VALUE_OPTIONAL, 'Number of records to process per chunk'],
            ['optimize-memory', null, InputOption::VALUE_NONE, 'Enable memory optimization for large exports'],
        ];
    }

    protected function getArguments(): array
    {
        return [
            ['exporter', InputArgument::OPTIONAL, 'The exporter class name'],
            ['path', InputArgument::OPTIONAL, 'The path to save the file'],
        ];
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'exporter' => ['What is the exporter class name?', 'E.g. Botble\Blog\Exporters\PostExporter'],
            'path' => ['Where do you want to save the file?', 'E.g. storage/app/exports'],
        ];
    }

    protected function possibleExporters(): array
    {
        $exporters = [];

        collect(
            Finder::create()
                ->files()
                ->in(platform_path())
                ->name('*.php')
                ->contains(Exporter::class)
        )
            ->map(function (SplFileInfo $file) use (&$exporters) {
                $class = $this->resolveExporterNamespace($file->getPathname());

                if (
                    class_exists($class)
                    && is_subclass_of($class, Exporter::class)
                ) {
                    $exporters[] = $class;
                }
            });

        return array_combine($exporters, $exporters);
    }

    protected function resolveExporterNamespace(string $path): string
    {
        $content = file_get_contents($path);
        $namespace = str($content)->after('namespace ')->before(';')->trim();
        $basename = basename($path, '.php');

        return $namespace . '\\' . $basename;
    }
}
