<?php

use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

afterEach(function () {
    App::setLocale('en');
});

it('availableTypes returns locale-aware label when locale is pt_BR and translation is registered', function () {
    config()->set('scoutify.types', []);

    App::setLocale('pt_BR');

    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Artigos PT'],
        'pt_BR',
        'scoutify'
    );

    // Register the model using the dynamically-resolved label at the active locale.
    // globalSearchLabel() consults Lang::has() + __() at request time, so with pt_BR
    // active and the translation key loaded, it returns the Portuguese label.
    $registry = app(GlobalSearchRegistry::class);
    $registry->register(Article::class, [
        'key'   => Article::globalSearchGroup(),
        'label' => Article::globalSearchLabel(),
        'icon'  => Article::globalSearchIcon(),
        'color' => Article::globalSearchColor(),
    ]);

    $component = Livewire::test(Modal::class);
    $types = $component->instance()->availableTypes();

    expect($types)
        ->toHaveCount(1)
        ->and($types[0]['label'])->toBe('Artigos PT');
});

it('availableTypes returns English label when locale is en and translation is registered for en', function () {
    config()->set('scoutify.types', []);

    app('translator')->addLines(
        ['scoutify.types.article_plural' => 'Articles EN'],
        'en',
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
    $types = $component->instance()->availableTypes();

    expect($types)
        ->toHaveCount(1)
        ->and($types[0]['label'])->toBe('Articles EN');
});
