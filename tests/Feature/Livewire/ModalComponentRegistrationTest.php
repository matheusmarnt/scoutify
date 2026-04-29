<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;
use Matheusmarnt\Scoutify\Support\LivewireVersion;

it('resolves scoutify::modal alias to Modal class', function () {
    $component = Livewire::test('scoutify::modal');

    expect($component->instance())->toBeInstanceOf(Modal::class);
});

it('registers component via addNamespace on Livewire v4', function () {
    $component = Livewire::test('scoutify::modal');

    expect($component->instance())->toBeInstanceOf(Modal::class);
})->skip(LivewireVersion::major() < 4, 'Livewire v4+ only');

it('registers component via Livewire::component on Livewire v3', function () {
    $component = Livewire::test('scoutify::modal');

    expect($component->instance())->toBeInstanceOf(Modal::class);
})->skip(LivewireVersion::major() >= 4, 'Livewire v3 only');
