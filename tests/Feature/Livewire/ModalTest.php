<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('starts closed', function () {
    Livewire::test(Modal::class)
        ->assertSet('isOpen', false);
});

it('opens on scoutify:open event', function () {
    Livewire::test(Modal::class)
        ->dispatch('scoutify:open')
        ->assertSet('isOpen', true);
});

it('closes and clears query', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->set('query', 'hello')
        ->call('close')
        ->assertSet('isOpen', false)
        ->assertSet('query', '');
});

it('search returns empty array for blank query', function () {
    Livewire::test(Modal::class)
        ->set('query', '')
        ->call('search')
        ->assertSet('results', []);
});
