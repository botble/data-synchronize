<?php

namespace Botble\DataSynchronize\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(name: 'data-synchronize:make:import-controller', description: 'Make a new import controller')]
class ImportControllerMakeCommand extends GeneratorCommand
{
    protected $type = 'Import Controller';

    protected function getStub(): string
    {
        return $this->resolveStubPath('import-controller');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Http\Controllers';
    }

    protected function buildClass($name): string
    {
        $contents = str_replace(
            '{{ importer }}',
            $this->argument('importer'),
            parent::buildClass($name)
        );

        return str_replace(
            '{{ importerNamespace }}',
            trim($this->rootNamespace(), '\\') . '\\Importers\\' . $this->argument('importer'),
            $contents
        );
    }

    protected function getArguments(): array
    {
        return [
            ...parent::getArguments(),
            ['importer', InputArgument::REQUIRED, 'The name of the importer'],
        ];
    }
}
