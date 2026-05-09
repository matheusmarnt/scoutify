<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\ScoutifyManager;

it('getDialogContent returns null by default', function () {
    expect(app(ScoutifyManager::class)->theme()->getDialogContent())->toBeNull();
});

it('dialogContent setter stores and returns the custom class string', function () {
    $theme = app(ScoutifyManager::class)->theme();
    $theme->dialogContent('custom-bg-class');

    expect($theme->getDialogContent())->toBe('custom-bg-class');
});

it('shell renders all default content classes when dialogContent is not configured', function () {
    Artisan::call('view:clear');

    $html = Blade::render('<x-scoutify::gs.shell></x-scoutify::gs.shell>');

    expect($html)
        ->toContain('flex')
        ->toContain('max-h-[90dvh]')
        ->toContain('overflow-hidden')
        ->toContain('rounded-t-2xl')
        ->toContain('bg-white')
        ->toContain('dark:bg-zinc-900');
});

it('shell renders custom content class when dialogContent is configured', function () {
    Artisan::call('view:clear');

    app(ScoutifyManager::class)->theme()->dialogContent('custom-content-class-xyz');

    $html = Blade::render('<x-scoutify::gs.shell></x-scoutify::gs.shell>');

    expect($html)->toContain('custom-content-class-xyz');
});

it('dialogContent supports fluent chaining with other theme methods', function () {
    $theme = Scoutify::theme()
        ->dialogContent('bg-slate-800')
        ->dialogPanel('relative w-full md:max-w-4xl');

    expect($theme->getDialogContent())->toBe('bg-slate-800');
    expect($theme->getDialogPanel())->toBe('relative w-full md:max-w-4xl');
});
