<?php

use Livewire\Livewire;
use Matheusmarnt\Scoutify\Livewire\Modal;

it('search-field renders with x-data composing state', function () {
    Livewire::test(Modal::class)
        ->assertSeeHtml('composing');
});
