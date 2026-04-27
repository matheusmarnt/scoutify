<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('renders idle-state magnifier prompt when query is blank and recents enabled', function () {
    config(['scoutify.recents.enabled' => true]);

    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertSee('Type to search across the entire system');
});

it('renders idle-state when query is blank and recents disabled', function () {
    config(['scoutify.recents.enabled' => false]);

    Livewire::test(Modal::class)
        ->set('query', '')
        ->assertSee('Type to search across the entire system');
});
