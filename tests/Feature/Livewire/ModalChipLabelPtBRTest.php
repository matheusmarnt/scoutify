<?php

use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

afterEach(function () {
    App::setLocale('en');
});

it('mounted Modal availableTypes property contains Portuguese label when APP_LOCALE is pt_BR', function () {
    config()->set('scoutify.types', []);

    App::setLocale('pt_BR');

    // Register the pt_BR translation key for the Article model type chip.
    // Key pattern: scoutify.types.<snake_class>_plural → article_plural
    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    // Register the Article model in the registry.
    // globalSearchLabel() is called HERE at request time with pt_BR locale active,
    // which is the behaviour guaranteed by the fix (label not cached at build time).
    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key'   => Article::globalSearchGroup(),
        'label' => Article::globalSearchLabel(),
        'icon'  => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    $component = Livewire::test(Modal::class);

    $availableTypes = $component->instance()->availableTypes();

    $labels = array_column($availableTypes, 'label');

    expect($labels)->toContain('Artigos');
});

it('mounted Modal availableTypes label is NOT English fallback when pt_BR translation is active', function () {
    config()->set('scoutify.types', []);

    App::setLocale('pt_BR');

    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos'],
        'pt_BR',
        'scoutify'
    );

    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key'   => Article::globalSearchGroup(),
        'label' => Article::globalSearchLabel(),
        'icon'  => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    $component = Livewire::test(Modal::class);

    $availableTypes = $component->instance()->availableTypes();
    $labels = array_column($availableTypes, 'label');

    // With the fix in place, label is resolved at request time via globalSearchLabel(),
    // so it must be 'Artigos', not the English fallback 'Articles'.
    expect($labels)->not->toContain('Articles')
        ->and($labels)->toContain('Artigos');
});
