<?php

use Illuminate\Support\Facades\Route;
use Matheusmarnt\Scoutify\Http\Controllers\PreviewStreamController;

Route::middleware(config('scoutify.preview.middleware', ['web']))
    ->prefix(config('scoutify.preview.route_prefix', 'scoutify/preview'))
    ->name('scoutify.preview.')
    ->group(function () {
        Route::get('stream', [PreviewStreamController::class, 'stream'])->name('stream');
        Route::get('download', [PreviewStreamController::class, 'download'])->name('download');
    });
