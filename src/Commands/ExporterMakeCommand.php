<?php

namespace Botble\DataSynchronize\Commands;

use Illuminate\Support\Stringable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'data-synchronize:make:exporter', description: 'Make a new exporter')]
class ExporterMakeCommand extends GeneratorCommand
{
    protected $type = 'Exporter';

    public function handle(): void
    {
        if (parent::handle() === null) {
            $this->promptCreateAdditional();

            $this->components->info(sprintf('Exporter <comment>%s</comment> has been created successfully.', $this->getNameInput()));
        }
    }

    protected function promptCreateAdditional(): void
    {
        $createController = confirm('Do you want to create a controller for this exporter?', true);
        $createPermission = confirm('Do you want to create a permission for this exporter?', true);
        $createRoute = confirm('Do you want to create a route for this exporter?', true);
        $panelSection = confirm('Do you want to register this exporter in the Export/Import Data panel section?', true);

        if ($createController) {
            $this->createController();
        }

        if ($createPermission) {
            $this->createPermission();
        }

        if ($createRoute) {
            $this->createRoute();
        }

        if ($panelSection) {
            $this->createPanelSection();
        }
    }

    protected function replacePluralName(string $stub): string
    {
        return str_replace('{{ pluralName }}', $this->getPluralName(), $stub);
    }

    protected function buildClass($name): string
    {
        return $this->replacePluralName(
            parent::buildClass($name)
        );
    }

    protected function getPluralName(): Stringable
    {
        return str($this->getNameInput())
            ->replace('Exporter', '')
            ->plural();
    }

    protected function createController(): void
    {
        $name = str_replace('Exporter', '', $this->getNameInput());

        $this->call('data-synchronize:make:export-controller', [
            'name' => "Export{$name}Controller",
            'exporter' => "{$name}Exporter",
            'plugin' => $this->argument('plugin'),
        ]);
    }

    protected function createPermission(): void
    {
        $name = $this->getPluralName();

        $stub = <<<'PHP'
        [
            'name' => 'Export {{ name }}',
            'flag' => '{{ flag }}',
            'parent_flag' => 'tools.data-synchronize',
        ],
        PHP;

        $permission = str($stub)
            ->replace('{{ name }}', $name->title())
            ->replace('{{ flag }}', $this->getPermissionFlag());

        $this->components->info(sprintf(
            'Please add following code into the <comment>%s</comment> file:',
            plugin_path("{$this->argument('plugin')}/config/permissions.php")
        ));

        info($permission);
    }

    protected function createRoute(): void
    {
        $stub = <<<'PHP'
        use {{ namespace }};

        Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function () {
            Route::prefix('export')->name('export.')->group(function () {
                Route::group(['prefix' => '{{ route }}', 'as' => '{{ route }}.', 'permission' => '{{ permission }}'], function () {
                    Route::get('/', [{{ controllerName }}::class, 'index'])->name('index');
                    Route::post('/', [{{ controllerName }}::class, 'store'])->name('store');
                });
            });
        });
        PHP;

        $name = $this->getPluralName();
        $singularName = $name->singular();
        $controllerName = "Export{$singularName}Controller";

        $route = str($stub)
            ->replace('{{ namespace }}', "{$this->rootNamespace()}Http\Controllers\\$controllerName")
            ->replace('{{ permission }}', $this->getPermissionFlag())
            ->replace('{{ route }}', $name->slug())
            ->replace('{{ controllerName }}', $controllerName);

        $this->components->info(sprintf(
            'Please add following code into the <comment>%s</comment> file:',
            plugin_path("{$this->argument('plugin')}/routes/web.php")
        ));

        info($route);
    }

    /**
     *
     */
    protected function createPanelSection(): void
    {
        $stub = <<<'PHP'
        use Botble\Base\Facades\PanelSectionManager;
        use Botble\Base\PanelSections\PanelSectionItem;
        use Botble\DataSynchronize\PanelSections\ExportPanelSection;

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function () {
            PanelSectionManager::default()->registerItem(
                ExportPanelSection::class,
                fn () => PanelSectionItem::make('{{ name }}')
                    ->setTitle('{{ title }}')
                    ->withDescription('{{ description }}')
                    ->withPriority(999)
                    ->withPermission('{{ permission }}')
                    ->withRoute('tools.data-synchronize.export.{{ route }}.index')
            );
        });
        PHP;

        $name = $this->getPluralName();

        $panelSection = str($stub)
            ->replace('{{ name }}', $name->slug())
            ->replace('{{ title }}', $name->title())
            ->replace('{{ description }}', "Export {$name->title()} data to a CSV or Excel file.")
            ->replace('{{ permission }}', $this->getPermissionFlag())
            ->replace('{{ route }}', $name->slug());

        $this->components->info('Please add following code into the <comment>boot()</comment> method of the plugin service provider:');

        info($panelSection);
    }

    protected function getPermissionFlag(): string
    {
        return "{$this->getPluralName()->slug()}.export";
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('exporter');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Exporters';
    }

    protected function getNameInput(): string
    {
        $name = parent::getNameInput();

        if (str_ends_with($name, 'Exporter')) {
            return $name;
        }

        return "{$name}Exporter";
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            ...parent::promptForMissingArgumentsUsing(),
            'name' => [
                'What should the ' . strtolower($this->type) . ' be named?',
                'E.g. PostExporter or Post',
            ],
        ];
    }
}
