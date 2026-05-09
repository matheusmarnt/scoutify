<?php

use Matheusmarnt\Scoutify\ScoutifyManager;

$defaultInputClass = 'block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-scoutify-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden';

it('input default carries full-width and layout classes', function () use ($defaultInputClass) {
    $input = app(ScoutifyManager::class)->theme()->getInput() ?? $defaultInputClass;

    expect($input)
        ->toContain('block')
        ->toContain('w-full')
        ->toContain('rounded-lg');
});

it('input default carries light and dark background classes', function () use ($defaultInputClass) {
    $input = app(ScoutifyManager::class)->theme()->getInput() ?? $defaultInputClass;

    expect($input)
        ->toContain('bg-white')
        ->toContain('dark:bg-zinc-900');
});

it('input default carries light and dark text classes', function () use ($defaultInputClass) {
    $input = app(ScoutifyManager::class)->theme()->getInput() ?? $defaultInputClass;

    expect($input)
        ->toContain('text-zinc-900')
        ->toContain('dark:text-zinc-100');
});

it('input default carries border and focus ring classes', function () use ($defaultInputClass) {
    $input = app(ScoutifyManager::class)->theme()->getInput() ?? $defaultInputClass;

    expect($input)
        ->toContain('border')
        ->toContain('focus-visible:ring-2')
        ->toContain('focus-visible:ring-scoutify-accent/30');
});

it('input default hides webkit search decorations', function () use ($defaultInputClass) {
    $input = app(ScoutifyManager::class)->theme()->getInput() ?? $defaultInputClass;

    expect($input)
        ->toContain('[&::-webkit-search-cancel-button]:hidden')
        ->toContain('[&::-webkit-search-decoration]:hidden');
});

it('input custom value overrides default when set via ThemeConfig', function () use ($defaultInputClass) {
    $theme = app(ScoutifyManager::class)->theme();
    $theme->input('custom-class-override');

    $input = $theme->getInput() ?? $defaultInputClass;

    expect($input)->toBe('custom-class-override');
});
