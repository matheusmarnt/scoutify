<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('triggers search when activeTypes is set directly', function () {
    Livewire::test(Modal::class)
        ->set('query', 'test')
        ->set('activeTypes', ['articles'])
        ->assertSet('activeIndex', 0);
});

it('resets activeIndex to 0 when activeTypes changes', function () {
    Livewire::test(Modal::class)
        ->set('activeIndex', 3)
        ->set('query', 'test')
        ->set('activeTypes', ['articles'])
        ->assertSet('activeIndex', 0);
});
