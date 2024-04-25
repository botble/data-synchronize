<?php

namespace Botble\DataSynchronize\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'data-synchronize:make:export-controller', description: 'Make a new export controller')]
class ExportControllerMakeCommand extends GeneratorCommand
{
    protected $type = 'Export Controller';

    protected function getStub(): string
    {
        return $this->resolveStubPath('export-controller');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http\Controllers';
    }

    protected function buildClass($name): string
    {
        $contents = str_replace(
            '{{ exporter }}',
            $this->argument('exporter'),
            parent::buildClass($name)
        );

        return str_replace(
            '{{ exporterNamespace }}',
            trim($this->rootNamespace(), '\\') . '\\Exporters\\' . $this->argument('exporter'),
            $contents
        );
    }

    protected function getArguments(): array
    {
        return [
            ...parent::getArguments(),
            ['exporter', InputArgument::REQUIRED, 'The name of the exporter'],
        ];
    }
}
