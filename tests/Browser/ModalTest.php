<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('opens when scoutify:open event is dispatched', function () {
    Livewire::test(Modal::class)
        ->dispatch('scoutify:open')
        ->assertSet('isOpen', true);
});

it('closes via close() method', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->set('query', 'search term')
        ->call('close')
        ->assertSet('isOpen', false);
});

it('searches and returns results', function () {
    Livewire::test(Modal::class)
        ->set('query', 'test')
        ->call('search')
        ->assertSet('results', []); // no types configured → empty, but no exception
});

it('resets query on close', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->set('query', 'hello world')
        ->call('close')
        ->assertSet('query', '');
});

it('returns empty results for blank query', function () {
    Livewire::test(Modal::class)
        ->set('query', '')
        ->call('search')
        ->assertSet('results', []);
});

it('filters by active types', function () {
    Livewire::test(Modal::class)
        ->set('query', 'test')
        ->set('activeTypes', ['articles'])
        ->call('search')
        ->assertSet('results', []); // no types configured → empty after filter
});

it('opens with preset query and runs search', function () {
    Livewire::test(Modal::class)
        ->dispatch('scoutify:open', preset: 'preset term')
        ->assertSet('isOpen', true)
        ->assertSet('query', 'preset term');
});

it('resets activeIndex to 0 when query updates', function () {
    Livewire::test(Modal::class)
        ->set('activeIndex', 3)
        ->set('query', 'new query')
        ->assertSet('activeIndex', 0);
});

it('resets activeIndex to 0 when includeTrashed toggles', function () {
    Livewire::test(Modal::class)
        ->set('activeIndex', 2)
        ->set('query', 'test')
        ->set('includeTrashed', true)
        ->assertSet('activeIndex', 0);
});

it('resets activeIndex to 0 when onlyActive toggles', function () {
    Livewire::test(Modal::class)
        ->set('activeIndex', 5)
        ->set('query', 'test')
        ->set('onlyActive', true)
        ->assertSet('activeIndex', 0);
});

it('toggles a type into activeTypes', function () {
    Livewire::test(Modal::class)
        ->call('toggleType', 'articles')
        ->assertSet('activeTypes', ['articles']);
});

it('removes a type from activeTypes when toggled again', function () {
    Livewire::test(Modal::class)
        ->set('activeTypes', ['articles'])
        ->call('toggleType', 'articles')
        ->assertSet('activeTypes', []);
});

it('dispatches scoutify:opened when opened', function () {
    Livewire::test(Modal::class)
        ->dispatch('scoutify:open')
        ->assertDispatched('scoutify:opened');
});

it('dispatches scoutify:closed when closed', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->call('close')
        ->assertDispatched('scoutify:closed');
});

it('navigateTo closes the modal and redirects', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->call('navigateTo', 'https://example.com')
        ->assertSet('isOpen', false);
});

it('navigateTo with blank url returns null without redirecting', function () {
    Livewire::test(Modal::class)
        ->set('isOpen', true)
        ->call('navigateTo', '')
        ->assertSet('isOpen', false);
});
