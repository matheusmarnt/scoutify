<?php

use Illuminate\Support\Facades\Blade;

it('renders the trigger component', function () {
    $html = Blade::render('<x-scoutify::gs.trigger />');

    expect($html)
        ->toContain('scoutify:open')
        ->toContain('button');
});

it('includes the keyboard shortcut hint', function () {
    $html = Blade::render('<x-scoutify::gs.trigger />');

    expect($html)->toContain('⌘K');
});

it('hides the label when label prop is false', function () {
    $html = Blade::render('<x-scoutify::gs.trigger :label="false" />');

    // The label span should not be rendered when label=false
    expect($html)->not->toContain('hidden lg:inline"');
});

it('renders with custom class via attributes', function () {
    $html = Blade::render('<x-scoutify::gs.trigger class="my-custom-class" />');

    expect($html)->toContain('my-custom-class');
});
