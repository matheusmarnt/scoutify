<?php

use Matheusmarnt\Scoutify\Support\ThemeConfig;

it('starts with null values (all overridable)', function () {
    $theme = new ThemeConfig;

    expect($theme->getInput())->toBeNull()
        ->and($theme->getTrigger())->toBeNull()
        ->and($theme->getTriggerMobile())->toBeNull()
        ->and($theme->getToggleActive())->toBeNull()
        ->and($theme->getToggleInactive())->toBeNull()
        ->and($theme->getDialogScrim())->toBeNull()
        ->and($theme->getDialogPanel())->toBeNull()
        ->and($theme->getAccent())->toBeNull();
});

it('fluent setters return $this', function () {
    $theme = new ThemeConfig;

    expect($theme->input('cls'))->toBe($theme)
        ->and($theme->trigger('cls'))->toBe($theme)
        ->and($theme->triggerMobile('cls'))->toBe($theme)
        ->and($theme->toggleActive('cls'))->toBe($theme)
        ->and($theme->toggleInactive('cls'))->toBe($theme)
        ->and($theme->dialogScrim('cls'))->toBe($theme)
        ->and($theme->dialogPanel('cls'))->toBe($theme)
        ->and($theme->accent('#fff'))->toBe($theme)
        ->and($theme->color('coral', 'bg-coral-100', 'dark:bg-coral-900/60'))->toBe($theme);
});

it('stores and returns class overrides', function () {
    $theme = new ThemeConfig;
    $theme->input('custom-input-class')
        ->trigger('custom-trigger-class')
        ->toggleActive('bg-red-500');

    expect($theme->getInput())->toBe('custom-input-class')
        ->and($theme->getTrigger())->toBe('custom-trigger-class')
        ->and($theme->getToggleActive())->toBe('bg-red-500');
});

it('resolves custom color classes as combined light+dark string', function () {
    $theme = new ThemeConfig;
    $theme->color('coral', 'bg-coral-100 text-coral-600', 'dark:bg-coral-900/60 dark:text-coral-200');

    expect($theme->resolveColorClasses('coral'))
        ->toBe('bg-coral-100 text-coral-600 dark:bg-coral-900/60 dark:text-coral-200');
});

it('returns null for unknown custom color', function () {
    $theme = new ThemeConfig;

    expect($theme->resolveColorClasses('nonexistent'))->toBeNull();
});

it('override replaces, not merges, the class string', function () {
    $theme = new ThemeConfig;
    $theme->input('first-class');
    $theme->input('second-class');

    expect($theme->getInput())->toBe('second-class');
});
