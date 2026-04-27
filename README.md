<p align="center">
    <img src="art/scoutify.png" alt="Scoutify" width="750" />
</p>

<p align="center">
    <a href="https://packagist.org/packages/matheusmarnt/scoutify"><img src="https://img.shields.io/packagist/v/matheusmarnt/scoutify.svg?style=flat-square" alt="Latest Version on Packagist" /></a>
    <a href="https://github.com/matheusmarnt/scoutify/actions?query=workflow%3Atests+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/matheusmarnt/scoutify/tests.yml?branch=main&label=tests&style=flat-square" alt="Tests" /></a>
    <a href="https://github.com/matheusmarnt/scoutify/actions?query=workflow%3Apint+branch%3Amain"><img src="https://img.shields.io/github/actions/workflow/status/matheusmarnt/scoutify/pint.yml?branch=main&label=code+style&style=flat-square" alt="Code Style" /></a>
    <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="License" /></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11%7C12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel" /></a>
    <a href="https://livewire.laravel.com"><img src="https://img.shields.io/badge/Livewire-3%7C4-FB70A9?style=flat-square" alt="Livewire" /></a>
    <a href="https://pestphp.com"><img src="https://img.shields.io/badge/Pest-3%7C4-16B280?style=flat-square" alt="Pest" /></a>
    <a href="https://laravel.com/docs/scout"><img src="https://img.shields.io/badge/Scout-11-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Scout" /></a>
</p>

# Scoutify

⌘K global search modal for Laravel — multi-model Livewire UI powered by Scout.

Scoutify drops a **production-ready ⌘K search experience** into any Laravel application with a single Artisan command. Register your Eloquent models, choose your Scout driver — Meilisearch, Algolia, or Typesense — and ship a keyboard-triggered modal that queries multiple model types simultaneously, groups results by type, and persists recent search history to session.

The UI is a zero-JavaScript Livewire component with built-in type filtering, dark mode, WCAG AA accessibility, and mobile-first layout. Every CSS class is overridable via `config/scoutify.php` — no view publishing required for basic customization. Ships with translations for `en`, `pt_BR`, and `es`.

---

## Features

- **Livewire modal** — keyboard-triggered (`⌘K` / `Ctrl+K`) global search dialog
- **Multiple model types** — search across any number of Eloquent models simultaneously
- **Recent searches** — configurable history, persisted to session
- **i18n** — ships with `pt_BR`, `en`, and `es` translations
- **Dark mode** — full dark mode support out of the box
- **Mobile-first** — fully responsive, touch-friendly
- **WCAG AA** — accessible markup with focus management
- **Tailwind v4** — utility classes inlined, override via config

## Requirements

- PHP `^8.2`
- Laravel `^11.0 || ^12.0`
- Livewire `^3.0 || ^4.0`
- Tailwind CSS `^4.0`
- Laravel Scout `^10.0`

## Installation

```bash
composer require matheusmarnt/scoutify
```

## Setup

Run the interactive installer to choose your Scout driver and publish the config:

```bash
php artisan scoutify:install
```

This will:
1. Prompt for a Scout driver (`meilisearch`, `algolia`, or `typesense`)
2. Install the driver's Composer packages
3. Publish `config/scout.php` and `config/scoutify.php`
4. Set `SCOUT_DRIVER` in `.env`

## Registering Models

Make your Eloquent models globally searchable:

```bash
php artisan scoutify:searchable
```

The command discovers Eloquent models under `app/Models/`, prompts you to pick which to register (or pass `--all`), and **automatically edits each chosen model file** to:

1. Import `Matheusmarnt\Scoutify\Concerns\Searchable` and `Matheusmarnt\Scoutify\Contracts\GloballySearchable`
2. Add `implements GloballySearchable` to the class declaration
3. Insert `use Searchable;` as the first statement in the class body

The `Searchable` trait already provides sensible defaults for every interface method (`globalSearchTitle`, `globalSearchUrl`, etc.), so your model is searchable instantly. Override any method later for custom behavior:

```php
public function globalSearchTitle(): string
{
    return $this->title;
}

public function globalSearchUrl(): string
{
    return route('articles.show', $this);
}

public static function globalSearchGroup(): string
{
    return 'Articles';
}

public static function globalSearchIcon(): string
{
    return 'heroicon-o-document-text';
}

public static function globalSearchColor(): string
{
    return 'blue';
}
```

Re-running the command is safe — it tops up only what's missing on partially-registered models.

Use `--dry-run` to preview the planned edits without touching files:

```bash
php artisan scoutify:searchable --dry-run
```

Then import your models into the Scout index:

```bash
php artisan scoutify:import
```

## Usage

Add the trigger button and modal to your layout:

```blade
<x-scoutify::gs.trigger />
<livewire:scoutify::modal />
```

The trigger renders a `⌘K` / `Ctrl+K` button. The modal opens automatically on keyboard shortcut or when the `scoutify:open` Livewire event is dispatched.

Dispatch from anywhere:

```blade
<button x-on:click="$dispatch('scoutify:open')">Search</button>
```

## Tailwind CSS v4

Add Scoutify's views as a Tailwind source so utility classes are included in your build:

```css
/* resources/css/app.css */
@source "../vendor/matheusmarnt/scoutify/resources/views/**/*.blade.php";
```

## Customization

### Class overrides

All UI classes are configurable via `config/scoutify.php`:

```php
'classes' => [
    'trigger'        => 'flex items-center gap-2 ...',
    'dialog_scrim'   => 'fixed inset-0 ...',
    'dialog_panel'   => 'relative bg-white ...',
    'input'          => 'w-full border-0 ...',
    'toggle_active'  => 'bg-blue-100 text-blue-700 ...',
    'toggle_inactive'=> 'text-gray-500 ...',
],
```

### Publish views

```bash
php artisan vendor:publish --tag=scoutify-views
```

### Publish translations

```bash
php artisan vendor:publish --tag=scoutify-translations
```

## Commands Reference

| Command | Description |
|---|---|
| `scoutify:install` | Install driver packages and publish config |
| `scoutify:searchable` | Register models as globally searchable |
| `scoutify:import` | Import all registered models into Scout index |
| `scoutify:flush` | Flush all registered models from Scout index |
| `scoutify:sync` | Flush then re-import (shortcut) |

## Testing

```bash
composer test          # Run test suite
composer test:coverage # Run with coverage (requires xdebug or pcov, min 90%)
```

## License

MIT — see [LICENSE](LICENSE.md).
