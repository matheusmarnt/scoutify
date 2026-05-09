<?php

use Illuminate\Support\Facades\Blade;
use Matheusmarnt\Scoutify\ScoutifyManager;

it('result-row resolves tileClasses from built-in Color enum for blue', function () {
    $html = Blade::render(
        '<x-scoutify::gs.result-row url="/test" icon="heroicon-o-star" group-color="blue" title-html="Test" :index="0" />',
    );

    expect($html)->toContain('bg-blue-100');
});

it('result-row falls back to zinc tileClasses for unknown color', function () {
    $html = Blade::render(
        '<x-scoutify::gs.result-row url="/test" icon="heroicon-o-star" group-color="unknown-color" title-html="Test" :index="0" />',
    );

    expect($html)->toContain('bg-zinc-100');
});

it('group-header receives color prop and renders colored tile from enum', function () {
    $html = Blade::render(
        '<x-scoutify::gs.group-header icon="heroicon-o-folder" label="Articles" color="blue" />',
    );

    expect($html)->toContain('bg-blue-100');
});

it('ThemeConfig custom color overrides built-in enum for resolveClasses', function () {
    app(ScoutifyManager::class)
        ->theme()
        ->color('coral', 'bg-coral-100 text-coral-600', 'dark:bg-coral-900/60 dark:text-coral-200');

    $html = Blade::render(
        '<x-scoutify::gs.result-row url="/test" icon="heroicon-o-star" group-color="coral" title-html="Test" :index="0" />',
    );

    expect($html)->toContain('bg-coral-100');
});
