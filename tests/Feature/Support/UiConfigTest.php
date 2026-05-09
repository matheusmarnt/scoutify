<?php

use Illuminate\View\Component;
use Matheusmarnt\Scoutify\Support\UiConfig;

it('has correct defaults', function () {
    $ui = new UiConfig;

    expect($ui->typeChipsVisible())->toBeTrue()
        ->and($ui->toggleOnlyActiveVisible())->toBeTrue()
        ->and($ui->toggleIncludeTrashedVisible())->toBeTrue()
        ->and($ui->hintBarVisible())->toBeTrue()
        ->and($ui->idleHintVisible())->toBeTrue()
        ->and($ui->getSlot('header_trailing'))->toBeNull();
});

it('fluent setters return $this', function () {
    $ui = new UiConfig;

    expect($ui->showTypeChips(false))->toBe($ui)
        ->and($ui->showToggleOnlyActive(false))->toBe($ui)
        ->and($ui->showToggleIncludeTrashed(false))->toBe($ui)
        ->and($ui->showHintBar(false))->toBe($ui)
        ->and($ui->showIdleHint(false))->toBe($ui)
        ->and($ui->slot('header_trailing', 'some.view'))->toBe($ui);
});

it('overrides default flags', function () {
    $ui = new UiConfig;
    $ui->showTypeChips(false)
        ->showToggleOnlyActive(false)
        ->showToggleIncludeTrashed(false)
        ->showHintBar(false)
        ->showIdleHint(false);

    expect($ui->typeChipsVisible())->toBeFalse()
        ->and($ui->toggleOnlyActiveVisible())->toBeFalse()
        ->and($ui->toggleIncludeTrashedVisible())->toBeFalse()
        ->and($ui->hintBarVisible())->toBeFalse()
        ->and($ui->idleHintVisible())->toBeFalse();
});

it('stores slots by name', function () {
    $ui = new UiConfig;
    $closure = fn ($ctx) => 'hello';

    $ui->slot('header_trailing', 'partials.brand')
        ->slot('after_results', $closure);

    expect($ui->getSlot('header_trailing'))->toBe('partials.brand')
        ->and($ui->getSlot('after_results'))->toBe($closure)
        ->and($ui->getSlot('nonexistent'))->toBeNull();
});

it('accepts string, closure, and class-string as slot values', function () {
    $ui = new UiConfig;
    $class = Component::class;

    $ui->slot('a', 'view.name')
        ->slot('b', fn () => '')
        ->slot('c', $class);

    expect($ui->getSlot('a'))->toBeString()
        ->and($ui->getSlot('b'))->toBeCallable()
        ->and($ui->getSlot('c'))->toBe($class);
});
