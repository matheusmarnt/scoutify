<?php

use Matheusmarnt\Scoutify\Enums\Color;

it('has a case for every official TailwindCSS v4 color', function () {
    $expected = [
        'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald',
        'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple',
        'fuchsia', 'pink', 'rose',
        'slate', 'gray', 'zinc', 'neutral', 'stone',
        'taupe', 'mauve', 'mist', 'olive',
    ];

    foreach ($expected as $value) {
        expect(Color::tryFrom($value))->not->toBeNull("Color::{$value} is missing");
    }
});

it('tileClasses returns non-empty string for every case', function () {
    foreach (Color::cases() as $color) {
        expect($color->tileClasses())->toBeString()->not->toBeEmpty();
    }
});

it('chromatic colors carry bg and text classes for light and dark', function () {
    $classes = Color::Blue->tileClasses();

    expect($classes)
        ->toContain('bg-blue-100')
        ->toContain('text-blue-600')
        ->toContain('dark:bg-blue-900/40')
        ->toContain('dark:text-blue-300');
});

it('neutral colors use solid dark bg without opacity', function () {
    foreach ([Color::Zinc, Color::Slate, Color::Gray, Color::Neutral, Color::Stone] as $color) {
        $classes = $color->tileClasses();
        expect($classes)
            ->not->toContain('/40', "Neutral {$color->value} should not use opacity in dark mode")
            ->toContain("dark:bg-{$color->value}-800");
    }
});

it('warm bright colors use text-700 for better contrast', function () {
    foreach ([Color::Amber, Color::Yellow, Color::Lime, Color::Olive] as $color) {
        expect($color->tileClasses())->toContain('text-'.$color->value.'-700');
    }
});

it('resolve returns the matching enum for a known string', function () {
    expect(Color::resolve('blue'))->toBe(Color::Blue);
    expect(Color::resolve('indigo'))->toBe(Color::Indigo);
    expect(Color::resolve('rose'))->toBe(Color::Rose);
});

it('resolve returns Zinc for unknown string', function () {
    expect(Color::resolve('coral'))->toBe(Color::Zinc);
    expect(Color::resolve(''))->toBe(Color::Zinc);
});

it('resolve returns the same enum when passed an enum instance', function () {
    expect(Color::resolve(Color::Teal))->toBe(Color::Teal);
});

it('resolveClasses returns tile classes for known string', function () {
    expect(Color::resolveClasses('blue'))->toBe(Color::Blue->tileClasses());
    expect(Color::resolveClasses('red'))->toBe(Color::Red->tileClasses());
});

it('resolveClasses returns tile classes when passed enum instance directly', function () {
    expect(Color::resolveClasses(Color::Purple))->toBe(Color::Purple->tileClasses());
});

it('resolveClasses falls back to config for unknown color token', function () {
    config(['scoutify.colors.coral' => 'bg-coral-100 text-coral-600']);

    expect(Color::resolveClasses('coral'))->toBe('bg-coral-100 text-coral-600');
});

it('resolveClasses falls back to zinc classes when color unknown and not in config', function () {
    expect(Color::resolveClasses('nonexistent-color'))->toBe(Color::Zinc->tileClasses());
});

it('all cases have distinct values', function () {
    $values = array_map(fn (Color $c) => $c->value, Color::cases());

    expect(count($values))->toBe(count(array_unique($values)));
});
