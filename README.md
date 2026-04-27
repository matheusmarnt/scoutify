# Scoutify

Advanced global search for Laravel — Livewire modal UI + Laravel Scout integration.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matheusmarnt/scoutify.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/scoutify)
[![Tests](https://img.shields.io/github/actions/workflow/status/matheusmarnt/scoutify/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/matheusmarnt/scoutify/actions?query=workflow%3Atests+branch%3Amain)
[![Code Style](https://img.shields.io/github/actions/workflow/status/matheusmarnt/scoutify/pint.yml?branch=main&label=code+style&style=flat-square)](https://github.com/matheusmarnt/scoutify/actions?query=workflow%3Apint+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/matheusmarnt/scoutify.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/scoutify)
[![License](https://img.shields.io/packagist/l/matheusmarnt/scoutify.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/scoutify)

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

This prompts you to select which models to register. For each selected model, add the trait and implement the interface:

```php
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class Article extends Model implements GloballySearchable
{
    use Searchable;

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
}
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
