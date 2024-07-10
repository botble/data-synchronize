<?php

namespace Botble\DataSynchronize\Commands;

use Illuminate\Support\Stringable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'data-synchronize:make:importer', description: 'Make a new importer')]
class ImporterMakeCommand extends GeneratorCommand
{
    protected $type = 'Importer';

    public function handle(): void
    {
        if (parent::handle() === null) {
            $this->promptCreateAdditional();

            $this->components->info(sprintf('Importer <comment>%s</comment> has been created successfully.', $this->getNameInput()));
        }
    }

    protected function promptCreateAdditional(): void
    {
        $createController = confirm('Do you want to create a controller for this importer?', true);
        $createPermission = confirm('Do you want to create a permission for this importer?', true);
        $createRoute = confirm('Do you want to create a route for this importer?', true);
        $panelSection = confirm('Do you want to register this importer in the Export/Import Data panel section?', true);

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

    protected function createController(): void
    {
        $name = str_replace('Importer', '', $this->getNameInput());

        $this->call('data-synchronize:make:import-controller', [
            'name' => "Import{$name}Controller",
            'importer' => "{$name}Importer",
            'plugin' => $this->argument('plugin'),
        ]);
    }

    protected function createPermission(): void
    {
        $name = $this->getPluralName();

        $stub = <<<'PHP'
        [
            'name' => 'Import {{ name }}',
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
            Route::prefix('import')->name('import.')->group(function () {
                Route::group(['prefix' => '{{ route }}', 'as' => '{{ route }}.', 'permission' => '{{ permission }}'], function () {
                    Route::get('/', [{{ controllerName }}::class, 'index'])->name('index');
                    Route::post('/', [{{ controllerName }}::class, 'import'])->name('store');
                    Route::post('validate', [{{ controllerName }}::class, 'validateData'])->name('validate');
                    Route::post('download-example', [{{ controllerName }}::class, 'downloadExample'])->name('download-example');
                });
            });
        });
        PHP;

        $name = $this->getPluralName();
        $singularName = $name->singular();
        $controllerName = "Import{$singularName}Controller";

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

    protected function createPanelSection(): void
    {
        $stub = <<<'PHP'
        use Botble\Base\Facades\PanelSectionManager;
        use Botble\Base\PanelSections\PanelSectionItem;
        use Botble\DataSynchronize\PanelSections\ImportPanelSection;

        PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function () {
            PanelSectionManager::default()->registerItem(
                ImportPanelSection::class,
                fn () => PanelSectionItem::make('{{ name }}')
                    ->setTitle('{{ title }}')
                    ->withDescription('{{ description }}')
                    ->withPriority(999)
                    ->withPermission('{{ permission }}')
                    ->withRoute('tools.data-synchronize.import.{{ route }}.index')
            );
        });
        PHP;

        $name = $this->getPluralName();

        $panelSection = str($stub)
            ->replace('{{ name }}', $name->slug())
            ->replace('{{ title }}', $name->title())
            ->replace('{{ description }}', "Import {$name->title()} data from CSV or Excel file.")
            ->replace('{{ permission }}', $this->getPermissionFlag())
            ->replace('{{ route }}', $name->slug());

        $this->components->info('Please add following code into the <comment>boot()</comment> method of the plugin service provider:');

        info($panelSection);
    }

    protected function getPermissionFlag(): string
    {
        return "{$this->getPluralName()->slug()}.import";
    }

    protected function buildClass($name): string
    {
        return $this->replacePluralName(
            parent::buildClass($name)
        );
    }

    protected function replacePluralName(string $stub): string
    {
        return str_replace(
            '{{ pluralNameLowercase }}',
            $this->getPluralName()->lower(),
            str_replace('{{ pluralName }}', $this->getPluralName(), $stub),
        );
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('importer');
    }

    protected function getPluralName(): Stringable
    {
        return str($this->getNameInput())
            ->replace('Importer', '')
            ->plural();
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Importers';
    }

    protected function getNameInput(): string
    {
        $name = parent::getNameInput();

        if (str_ends_with($name, 'Importer')) {
            return $name;
        }

        return "{$name}Importer";
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            ...parent::promptForMissingArgumentsUsing(),
            'name' => [
                'What should the ' . strtolower($this->type) . ' be named?',
                'E.g. PostImporter or Post',
            ],
        ];
    }
}
