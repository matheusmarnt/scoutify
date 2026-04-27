<?php

it('dialog_panel default carries outer-wrapper sizing classes', function () {
    $panel = config('scoutify.classes.dialog_panel');

    expect($panel)
        ->toContain('relative')
        ->toContain('w-full')
        ->toContain('md:max-w-2xl');
});

it('dialog_panel default does not duplicate inner-content visual styling', function () {
    $panel = config('scoutify.classes.dialog_panel');

    expect($panel)
        ->not->toContain('bg-white')
        ->not->toContain('md:shadow-2xl')
        ->not->toContain('rounded-t-2xl')
        ->not->toContain('max-h-[90dvh]');
});
