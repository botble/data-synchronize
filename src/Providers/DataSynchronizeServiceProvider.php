<?php

namespace Botble\DataSynchronize\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class DataSynchronizeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/data-synchronize')
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews();
    }
}
