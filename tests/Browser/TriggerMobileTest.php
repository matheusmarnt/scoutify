<?php

use Illuminate\Support\Facades\Blade;

it('renders mobile trigger as a button that dispatches scoutify:open', function () {
    config()->set('scoutify.classes.trigger_mobile', 'lg:hidden size-11');

    $html = Blade::render('<x-scoutify::gs.trigger-mobile />');

    expect($html)
        ->toContain('<button')
        ->toContain('type="button"')
        ->toContain("\$dispatch('scoutify:open')")
        ->toContain('aria-label')
        ->toContain('lg:hidden')
        ->toContain('size-11');
});

it('mobile trigger does not stop event propagation', function () {
    $source = file_get_contents(
        __DIR__.'/../../resources/views/components/gs/trigger-mobile.blade.php'
    );

    expect($source)
        ->toContain("\$dispatch('scoutify:open')")
        ->not->toContain('.stop');
});

it('mobile trigger uses magnifying-glass icon', function () {
    $source = file_get_contents(
        __DIR__.'/../../resources/views/components/gs/trigger-mobile.blade.php'
    );

    expect($source)->toContain('name="magnifying-glass"');
});
