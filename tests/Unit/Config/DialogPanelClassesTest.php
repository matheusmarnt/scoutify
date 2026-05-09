<?php

use Matheusmarnt\Scoutify\ScoutifyManager;

it('dialog panel default carries outer-wrapper sizing classes', function () {
    $panel = app(ScoutifyManager::class)->theme()->getDialogPanel()
        ?? 'relative w-full md:max-w-2xl';

    expect($panel)
        ->toContain('relative')
        ->toContain('w-full')
        ->toContain('md:max-w-2xl');
});

it('dialog panel default does not duplicate inner-content visual styling', function () {
    $panel = app(ScoutifyManager::class)->theme()->getDialogPanel()
        ?? 'relative w-full md:max-w-2xl';

    expect($panel)
        ->not->toContain('bg-white')
        ->not->toContain('md:shadow-2xl')
        ->not->toContain('rounded-t-2xl')
        ->not->toContain('max-h-[90dvh]');
});
