<?php

use Illuminate\Support\Facades\Blade;

it('result-row resolves tileClasses from config colors map for blue color', function () {
    config()->set('scoutify.colors.blue', 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300');

    $html = Blade::render(
        '<x-scoutify::gs.result-row url="/test" icon="heroicon-o-star" group-color="blue" title-html="Test" :index="0" />',
    );

    expect($html)->toContain('bg-blue-100');
});

it('result-row falls back to zinc tileClasses for unknown color', function () {
    config()->set('scoutify.colors.zinc', 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400');

    $html = Blade::render(
        '<x-scoutify::gs.result-row url="/test" icon="heroicon-o-star" group-color="unknown-color" title-html="Test" :index="0" />',
    );

    expect($html)->toContain('bg-zinc-100');
});

it('group-header receives color prop and renders colored tile', function () {
    config()->set('scoutify.colors.blue', 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300');

    $html = Blade::render(
        '<x-scoutify::gs.group-header icon="heroicon-o-folder" label="Articles" color="blue" />',
    );

    expect($html)->toContain('bg-blue-100');
});
