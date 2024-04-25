# Data Synchronize

## Usage

### Exporter

There are two ways to create an exporter.

![Exporter](./art/exporter.png)

#### Create an exporter using the command

You can use the `php artisan data-synchronize:make:exporter` command to create an exporter.

```bash
php artisan data-synchronize:make:exporter PostExporter
```

#### Manually create an exporter

This is how an exporter should look like, below is an example of a `PostExporter` class.

```php
<?php

namespace Botble\Blog\Exporters;

use Botble\Blog\Models\Post;
use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\Exporter;
use Illuminate\Support\Collection;

class PostExporter extends Exporter
{
    public function label(): string
    {
        return 'Posts';
    }

    public function columns(): array
    {
        return [
            ExportColumn::make('name'),
            ExportColumn::make('description'),
            ExportColumn::make('created_at'),
        ];
    }

    public function collection(): Collection
    {
        return Post::all();
    }
}
```

This is how to use the exporter in a controller.

```php
<?php

namespace Botble\Blog\Http\Controllers;

use Botble\DataSynchronize\Exporter\Exporter;
use Botble\DataSynchronize\Http\Controllers\ExportController;
use Botble\Blog\Exporters\PostExporter;

class ExportPostController extends ExportController
{
    protected function getExporter(): Exporter
    {
        return PostExporter::make();
    }
}
```

And then register the route in your routes file.

```php
use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;
use Botble\Blog\Http\Controllers\ExportPostController;

AdminHelper::registerRoutes(function () {
    Route::prefix('tools/data-synchronize')->name('tools.data-synchronize.')->group(function () {
        Route::group(['prefix' => 'export/posts', 'as' => 'export.posts.', 'permission' => 'posts.export'], function () {
            Route::get('/', [ExportPostController::class, 'index'])->name('index');
            Route::post('/', [ExportPostController::class, 'store'])->name('store');
        });
    });
});
```

Each exporter route should have a permission to access it. You can use the `permission` key in the route group to define
the permission.

In above route definition, the permission is `posts.export` key and it parent is `tools.data-synchronize`. You can
define the permission in the `permissions.php` of your plugin.

```php
return [
    [
        'name' => 'Export Posts',
        'flag' => 'posts.export',
        'parent_flag' => 'tools.data-synchronize',
    ],
];
```

Now you can navigate to `http://your-domain/tools/data-synchronize/export/posts` to export posts.

#### Add exporter to Export/Import Data panel section

![Panel Section](./art/panel-section-1.png)

To add the exporter to the Export/Import Data panel section, you can use the `beforeRendering` method of
the `PanelSectionManager` class to register the exporter into the panel section.

```php
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\DataSynchronize\PanelSections\ExportPanelSection;

public function boot(): void
{
    // ...

    PanelSectionManager::setGroupId('data-synchronize')->beforeRendering(function () {
        PanelSectionManager::default()
            ->registerItem(
                ExportPanelSection::class,
                fn () => PanelSectionItem::make('posts')
                    ->setTitle('Posts')
                    ->withDescription('Export post data to CSV or Excel file.')
                    ->withPriority(120)
                    ->withRoute('tools.data-synchronize.export.posts.index')
                    ->withPermission('posts.export')
            );
    });
    
    // ...
}
```

You can see the exporter in the **Export/Import Data** panel section.

![Panel Section](./art/panel-section-2.png)
