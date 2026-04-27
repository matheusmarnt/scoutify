<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('clearFilters resets activeTypes, includeTrashed, onlyActive', function () {
    Livewire::test(Modal::class)
        ->set('activeTypes', ['articles'])
        ->set('includeTrashed', true)
        ->set('onlyActive', true)
        ->call('clearFilters')
        ->assertSet('activeTypes', [])
        ->assertSet('includeTrashed', false)
        ->assertSet('onlyActive', false)
        ->assertSet('activeIndex', 0);
});

it('clearFilters triggers search', function () {
    Livewire::test(Modal::class)
        ->set('query', 'test')
        ->call('clearFilters')
        ->assertSet('results', []);
});

it('results-listbox blade source contains home/end/page-up/page-down handlers', function () {
    $path = realpath(__DIR__.'/../../../resources/views/components/gs/results-listbox.blade.php');
    $source = file_get_contents($path);
    expect($source)
        ->toContain('page-down')
        ->toContain('page-up')
        ->toContain('keydown.home')
        ->toContain('keydown.end');
});
