<?php

use Botble\Base\Facades\AdminHelper;
use Botble\DataSynchronize\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::prefix('data-synchronize')->name('data-synchronize.')->group(function () {
        Route::post('upload', [UploadController::class, '__invoke'])->name('upload');
    });
});
