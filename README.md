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
- **Grouped results** — results organised by model type with section headers
- **Multiple drivers** — Meilisearch, Algolia, Typesense, or Database
- **Recent searches** — configurable history, persisted to session
- **i18n** — ships with `pt_BR`, `en`, and `es` translations
- **Dark mode** — full dark mode support out of the box
- **WCAG AA** — accessible markup with focus management and keyboard navigation
- **Tailwind v4** — utility classes inlined, override via config

## Quick Start

```bash
composer require matheusmarnt/scoutify
php artisan scoutify:install
php artisan scoutify:searchable
php artisan scoutify:import
```

Add to your layout:

```blade
<x-scoutify::gs.trigger />
<livewire:scoutify::modal />
```

## Commands

| Command | Description |
|---|---|
| `scoutify:install` | Install driver packages, publish config, configure backend |
| `scoutify:doctor` | Verify driver config and backend connectivity |
| `scoutify:searchable` | Register models as globally searchable |
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
