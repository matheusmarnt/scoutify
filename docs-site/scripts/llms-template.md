# Scoutify

> v2.0.0 — Laravel Scout UI layer: global search modal (Livewire + Alpine.js) with grouped results, per-model authorization, icon resolution, HTML sanitization, and CLI install tooling.
>
> PHP ^8.2 | Laravel ^11|^12 | Package: `matheusmarnt/scoutify`

## Antipatterns (DO NOT GENERATE)

The following patterns cause runtime errors or incorrect behavior. Never generate them.

### legacy_config_icon_prefix (severity: error)
**Rule:** Never write `'icon_prefix'` in `config/scoutify.php`.
**Why:** Removed in v2.0.0. `ScoutifyServiceProvider::assertNoLegacyConfigKeys()` throws `RuntimeException` on boot.
**Fix:** Use `Scoutify::types()->iconPrefix('heroicon-o-')` in a service provider boot().

### legacy_config_types_array (severity: error)
**Rule:** Never write `'types' => [...]` in `config/scoutify.php`.
**Why:** Removed in v2.0.0. Same boot-time exception.
**Fix:** Use `Scoutify::types()->register(MyModel::class, 'Label', 'icon', Color::Blue)`.

### legacy_config_classes (severity: error)
**Rule:** Never write `'classes'` key in `config/scoutify.php`.
**Why:** Removed in v2.0.0. Boot throws.
**Fix:** Use `Scoutify::theme()->input('...')->trigger('...')`.

### legacy_config_colors (severity: error)
**Rule:** Never write `'colors'` key in `config/scoutify.php`.
**Why:** Removed in v2.0.0. Boot throws.
**Fix:** Use `Scoutify::theme()->color('name', 'light-class', 'dark-class')`.

### legacy_config_modal_ui (severity: error)
**Rule:** Never write `'modal' => ['ui' => ...]` in `config/scoutify.php`.
**Why:** Removed in v2.0.0. Boot throws.
**Fix:** Use `Scoutify::configureUi(fn (UiConfig $ui) => $ui->showTypeChips()->showHintBar(false))`.

### nonexistent_key_theme (severity: error)
**Rule:** Never write `'theme'` as a top-level key in `config/scoutify.php`.
**Why:** This key never existed in any version. It was a documentation error (corrected in PR #122).

### nonexistent_key_i18n (severity: error)
**Rule:** Never write `'i18n'` in `config/scoutify.php`.
**Why:** Never existed. Documentation error.

### nonexistent_key_behavior (severity: error)
**Rule:** Never write `'behavior'` in `config/scoutify.php`.
**Why:** Never existed. Documentation error.

### nonexistent_key_min_query_length (severity: error)
**Rule:** Never write `'min_query_length'` in `config/scoutify.php`.
**Why:** Never existed. Use `debounce_ms` instead.

### nonexistent_key_appearance_icon_prefix (severity: error)
**Rule:** Never write `'appearance' => ['icon_prefix' => ...]` in `config/scoutify.php`.
**Why:** Never existed. Documentation error.

### button_in_anchor (severity: error)
**Rule:** Never nest `<button>` inside `<a>` in Blade templates.
**Why:** HTML5 forbids interactive content inside interactive content. Livewire `wire:navigate` listener runs in capture phase — `@click.stop` cannot cancel it. Use the result-row overlay pattern (z-0 link + z-10 content div) instead.

### alpine_prevent_with_conditional_guard (severity: error)
**Rule:** Never use Alpine `.prevent` modifier with a conditional guard expression.
**Why:** `.prevent` calls `event.preventDefault()` BEFORE the expression evaluates, making the condition ineffective.
**Fix:** Remove `.prevent`, call `$event.preventDefault()` inside the `if` body instead.
**Wrong:** `@keydown.enter.prevent="if (condition) doThing()"`
**Correct:** `@keydown.enter="if (condition) { $event.preventDefault(); doThing(); }"`

## Page Index

{{PAGE_INDEX}}

## Reference Map

{{REFERENCE_MAP}}
