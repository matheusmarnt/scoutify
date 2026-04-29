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
    <a href="https://laravel.com/docs/scout"><img src="https://img.shields.io/badge/Scout-11-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Scout" /></a>
</p>

# Scoutify

⌘K global search modal for Laravel — multi-model Livewire UI powered by Scout.

Drops a production-ready ⌘K search experience into any Laravel application. Register Eloquent models, choose a Scout driver, and ship a keyboard-triggered modal that queries multiple model types simultaneously, groups results by type, and persists recent search history to session.

## Features

- **Livewire modal** — keyboard-triggered (`⌘K` / `Ctrl+K`) global search dialog
- **Zero-config discovery** — models under `app/Models/` using `Searchable` are auto-detected at boot
- **Grouped results** — results organised by model type with section headers and color tokens
- **Multiple drivers** — Meilisearch, Algolia, Typesense, or Database
- **Accent-insensitive highlight** — diacritic-free queries (`padrao`) match and highlight accented text (`Padrão`) via NFD normalization
- **Auto-discovered subtitles** — models with `description`, `subtitle`, `excerpt`, `summary`, `bio`, or `body` attributes surface them as result subtitles automatically, showing match context for fuzzy results
- **Query hook** — per-model `globalSearchBuilder()` for custom filters, scopes, or infix matching
- **Recent searches** — configurable history, persisted to session
- **i18n** — ships with `pt_BR`, `en`, and `es` translations
- **Dark mode** — full dark mode support out of the box
- **WCAG AA** — accessible markup with focus management and keyboard navigation
- **Tailwind v4** — utility classes inlined, override via config

## Quick Start

```bash
composer require matheusmarnt/scoutify
php artisan scoutify:install
```

This will:
1. Prompt for a Scout driver (`meilisearch`, `algolia`, or `typesense`)
2. Install the driver's Composer packages
3. Publish `config/scoutify.php`
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

The command then rebuilds the type manifest so models appear in the UI immediately.

The `Searchable` trait provides sensible defaults for every interface method. Override as needed:

```php
public function globalSearchTitle(): string      { return $this->title; }
public function globalSearchSubtitle(): ?string  { return $this->author; }
public function globalSearchUrl(): string        { return route('articles.show', $this); }

public static function globalSearchGroup(): string  { return 'Articles'; }
public static function globalSearchLabel(): string  { return 'Articles'; }  // UI chip label
public static function globalSearchIcon(): string   { return 'heroicon-o-document-text'; }
public static function globalSearchColor(): string  { return 'blue'; }
```

> **`globalSearchSubtitle()` auto-discovery:** if your model has a `description`, `subtitle`, `excerpt`, `summary`, `bio`, or `body` attribute, the trait returns it automatically (truncated to 150 chars). Override only when you need custom logic or a different field.

Use `--dry-run` to preview edits without touching files:

```bash
php artisan scoutify:searchable --dry-run
```

Then import your models into the Scout index:

```bash
php artisan scoutify:import
```

Add to your layout:

```blade
<x-scoutify::gs.trigger />
<livewire:scoutify::modal />
```

## Customizing the Scout Query

Override `globalSearchBuilder()` on any model to apply custom filters, scopes, or driver-specific options:

```php
use Laravel\Scout\Builder;

public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    return $builder->where('published', true);
}
```

> **Meilisearch note:** Meilisearch uses word-boundary prefix search. Substrings that are not word-prefixes (e.g. `"ano"` inside `"Mariano"`) return no results. If you need substring (infix) matching, override `globalSearchBuilder()` to configure Meilisearch's `attributesToSearchOn` or switch to the `database` driver which uses `LIKE`-based search.

## Opening the Modal Programmatically

Any element can open Scoutify without the official trigger component.

**Alpine (recommended):**
```html
<button x-data @click="$dispatch('scoutify:open')">Search</button>
```

**Plain JS / any context:**
```js
window.dispatchEvent(new CustomEvent('scoutify:open'))
```

**Inside a Livewire component:**
```html
<button wire:click="$dispatchTo('scoutify::modal', 'scoutify:open')">Search</button>
```

> **Do not use** `wire:click="$dispatch('scoutify:open')"` on plain Blade elements — outside a Livewire component tree, Livewire.js never initialises those directives.

## Commands

| Command | Description |
|---|---|
| `scoutify:install` | Install driver packages, publish config, configure backend |
| `scoutify:doctor` | Verify driver config and backend connectivity |
| `scoutify:searchable` | Register models as globally searchable and rebuild manifest |
| `scoutify:rebuild` | Rebuild the type manifest from `app/Models/` |
| `scoutify:import` | Import registered models into Scout index |
| `scoutify:flush` | Flush registered models from Scout index |
| `scoutify:sync` | Flush then re-import |

## Documentation

- [Installation guide](docs/installation.md) — step-by-step setup, model registration, Tailwind config, customization
- [Production deployment](docs/production.md) — per-driver production configuration (Meilisearch, Algolia, Typesense, Database)

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

MIT — see [LICENSE](LICENSE.md).
