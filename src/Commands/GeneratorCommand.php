<?php

namespace Botble\DataSynchronize\Commands;

use Illuminate\Console\GeneratorCommand as BaseGeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\search;
use function Laravel\Prompts\select;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GeneratorCommand extends BaseGeneratorCommand
{
    protected function rootNamespace(): string
    {
        return $this->getPluginNamespace() ?? $this->laravel->getNamespace();
    }

    protected function getPath($name): string
    {
        $name = str($name)
            ->replaceFirst($this->rootNamespace(), '')
            ->replace('\\', '/')
            ->toString();

        return plugin_path(sprintf('%s/src/%s.php', $this->argument('plugin'), $name));
    }

    protected function getArguments(): array
    {
        return [
            ['plugin', InputArgument::REQUIRED, "The plugin to create the $this->type in"],
            ...parent::getArguments(),
        ];
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if (! $input->getArgument('plugin')) {
            $input->setArgument('plugin', $this->promptPluginForCreating());
        }

        parent::promptForMissingArguments($input, $output);
    }

    protected function getPluginNamespace(): ?string
    {
        $plugin = File::json(plugin_path("{$this->argument('plugin')}/plugin.json"));

        return Arr::get($plugin, 'namespace');
    }

    protected function resolveStubPath($stub): string
    {
        return __DIR__ . "/../../stubs/$stub.stub";
    }

    protected function promptPluginForCreating(): string
    {
        $plugins = get_installed_plugins();

        if (windows_os()) {
            $plugin = select(sprintf('Select which plugin you want to create %s in:', strtolower($this->type)), $plugins);
        } else {
            $plugin = search(
                label: sprintf('Which plugin you want to create %s in:', strtolower($this->type)),
                options: fn ($search) => array_values(array_filter(
                    $plugins,
                    fn ($choice) => str_contains(strtolower($choice), strtolower($search))
                )),
                placeholder: 'Search...',
            );
        }

        if (! in_array($plugin, $plugins)) {
            $this->components->error('Plugin you selected is not exists.');

            return $this->promptPluginForCreating();
        }

        return $plugin;
    }
}
