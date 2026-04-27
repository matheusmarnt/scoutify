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

> **Running Artisan commands** — choose the invocation that matches your environment:
>
> | Environment | Artisan invocation |
> |---|---|
> | `php artisan serve` (host) | `php artisan <command>` |
> | Laravel Sail | `./vendor/bin/sail artisan <command>` |
> | Docker Compose (non-Sail) | `docker compose exec app php artisan <command>` |
>
> All `scoutify:*` commands below follow this pattern. Sail examples explicitly use `sail artisan`; Docker Compose examples use `docker compose exec app php artisan`.

`scoutify:install` always:

1. Prompts for a Scout driver (`meilisearch`, `algolia`, or `typesense`)
2. Installs the driver's Composer packages
3. Publishes `config/scoutify.php`
4. Sets `SCOUT_DRIVER` in `.env`
5. Configures the search backend for your environment
6. Runs `scoutify:doctor` automatically to verify the setup

### Meilisearch

#### Laravel Sail

```bash
./vendor/bin/sail composer require matheusmarnt/scoutify
./vendor/bin/sail artisan scoutify:install        # picks meilisearch, adds sail service, sets env vars
./vendor/bin/sail down && ./vendor/bin/sail up -d              # restart to bring the meilisearch container online
./vendor/bin/sail artisan scoutify:doctor         # verify connectivity
./vendor/bin/sail artisan scoutify:searchable     # register models
./vendor/bin/sail artisan scoutify:import         # index data
```

`scoutify:install` detects Sail automatically, runs `sail:add meilisearch` to add the service to `docker-compose.yml`, and sets `MEILISEARCH_HOST=http://meilisearch:7700` in `.env`.

#### Docker Compose (non-Sail)

```bash
composer require matheusmarnt/scoutify
docker compose exec app php artisan scoutify:install   # writes docker-compose.scoutify.yml + sets env vars
docker compose -f docker-compose.yml -f docker-compose.scoutify.yml up -d
docker compose exec app php artisan scoutify:doctor    # verify connectivity
docker compose exec app php artisan scoutify:searchable
docker compose exec app php artisan scoutify:import
```

`scoutify:install` detects an existing compose file (`docker-compose.yml`, `compose.yaml`, etc.), generates a `docker-compose.scoutify.yml` overlay with a Meilisearch service, and sets `MEILISEARCH_HOST=http://meilisearch:7700` in `.env`.

#### Host (`php artisan serve`)

```bash
# Start Meilisearch first (choose one):
docker run -d --name meilisearch -p 7700:7700 \
  -v $(pwd)/meili_data:/meili_data getmeili/meilisearch:latest
# or: https://www.meilisearch.com/docs/learn/getting_started/installation

composer require matheusmarnt/scoutify
php artisan scoutify:install         # sets SCOUT_DRIVER + MEILISEARCH_HOST=http://localhost:7700
php artisan scoutify:doctor          # verify connectivity
php artisan scoutify:searchable      # register models
php artisan scoutify:import          # index data
```

### Typesense

#### Laravel Sail

```bash
sail composer require matheusmarnt/scoutify
sail artisan scoutify:install        # picks typesense, adds Sail service, sets env vars
sail down && sail up -d
sail artisan scoutify:doctor
sail artisan scoutify:searchable
sail artisan scoutify:import
```

`scoutify:install` runs `sail:add typesense` and sets `TYPESENSE_HOST=typesense`, `TYPESENSE_PORT=8108`, `TYPESENSE_PROTOCOL=http`, and `TYPESENSE_API_KEY` in `.env`.

#### Docker Compose (non-Sail)

```bash
composer require matheusmarnt/scoutify
docker compose exec app php artisan scoutify:install   # writes docker-compose.scoutify.yml + sets env vars
docker compose -f docker-compose.yml -f docker-compose.scoutify.yml up -d
docker compose exec app php artisan scoutify:doctor
docker compose exec app php artisan scoutify:searchable
docker compose exec app php artisan scoutify:import
```

#### Host (`php artisan serve`)

```bash
# Start Typesense first:
docker run -d --name typesense -p 8108:8108 \
  -v $(pwd)/typesense_data:/data \
  typesense/typesense:latest \
  --data-dir /data --api-key=xyz --enable-cors
# or: https://typesense.org/docs/guide/install-typesense.html

composer require matheusmarnt/scoutify
php artisan scoutify:install         # sets SCOUT_DRIVER + TYPESENSE_* env vars
php artisan scoutify:doctor
php artisan scoutify:searchable
php artisan scoutify:import
```

### Algolia

Algolia is cloud-hosted — no local service needed. `scoutify:install` sets `SCOUT_DRIVER=algolia`, installs the client package, and adds `ALGOLIA_APP_ID` and `ALGOLIA_SECRET` placeholders to `.env`.

```bash
composer require matheusmarnt/scoutify
php artisan scoutify:install         # picks algolia, sets SCOUT_DRIVER + credential placeholders
# Fill in ALGOLIA_APP_ID and ALGOLIA_SECRET in .env
# Get credentials at: https://www.algolia.com/
php artisan scoutify:doctor          # verifies credentials are present
php artisan scoutify:searchable      # register models
php artisan scoutify:import          # index data
```

## Diagnostics

```bash
# Host (php artisan serve)
php artisan scoutify:doctor

# Laravel Sail
sail artisan scoutify:doctor

# Docker Compose (non-Sail)
docker compose exec app php artisan scoutify:doctor
```

Checks your driver configuration and connectivity. Reports the configured driver, the search backend URL, and whether it is reachable. Prints environment-aware remediation steps on failure:

```
  Scout driver: meilisearch
  Meilisearch host: http://meilisearch:7700
  ✓ Meilisearch reachable and healthy.
```

On failure inside a Sail container:

```
  ✗ Cannot reach Meilisearch at http://localhost:7700.
  Sail detected but MEILISEARCH_HOST points to localhost (wrong inside container).
  Fix: set MEILISEARCH_HOST=http://meilisearch:7700 in .env, then:
       sail down && sail up -d
```

Exit code `0` = healthy, `1` = issue found — usable in CI health checks.

## Registering Models

Make your Eloquent models globally searchable:

```bash
php artisan scoutify:searchable
```

The command discovers Eloquent models under `app/Models/`, prompts you to pick which to register (or pass `--all`), and **automatically edits each chosen model file** to:

1. Import `Matheusmarnt\Scoutify\Concerns\Searchable` and `Matheusmarnt\Scoutify\Contracts\GloballySearchable`
2. Add `implements GloballySearchable` to the class declaration
3. Insert `use Searchable;` as the first statement in the class body

The command also **injects a concrete `globalSearchUrl()` method** into the model, auto-resolved to the right URL for that model. All other interface methods (`globalSearchTitle`, `globalSearchGroup`, `globalSearchIcon`, `globalSearchColor`, `globalSearchSubtitle`) come from the `Searchable` trait — they already resolve dynamically based on the model name and attributes. Override them in your model at any time.

**Example — registering `App\Models\User` with a Filament resource present:**

```php
use App\Filament\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class User extends Model implements GloballySearchable
{
    use Searchable;

    public function globalSearchUrl(): string
    {
        return UserResource::getUrl('view', ['record' => $this]);
    }
}
```

All remaining interface methods are provided by the `Searchable` trait with sensible defaults. Override any of them directly in your model:

```php
// Title shown in bold in each search result row
// Default: $this->name
public function globalSearchTitle(): string
{
    return $this->full_name;
}

// Gray subtitle line below the title (null = hidden)
// Default: null
public function globalSearchSubtitle(): ?string
{
    return $this->email;
}

// Section header grouping results of this type
// Default: class basename, e.g. "User"
public static function globalSearchGroup(): string
{
    return 'Team Members';
}

// Heroicon name shown left of each result row
// Default: 'heroicon-o-magnifying-glass'
public static function globalSearchIcon(): string
{
    return 'heroicon-o-user';
}

// Icon tint colour (Tailwind colour name or 'gray')
// Default: 'gray'
public static function globalSearchColor(): string
{
    return 'blue';
}
```

### URL resolution cascade

When registering a model, the command detects the best URL in this order:

| Priority | Condition | Generated stub |
|---|---|---|
| 1 | Filament resource class exists | `UserResource::getUrl('view', ['record' => $this])` |
| 2 | Named route `{plural}.show` exists | `route('users.show', $this)` |
| 3 | Folio page `pages/users/[user].blade.php` exists | `url('/users/'.$this->getKey())` |
| 4 | None of the above | `// TODO: customize URL…` + `url('/')` placeholder |

The `Searchable` trait itself also applies the same cascade at runtime — so models registered with `--no-stubs` or those that already have the trait without a stub still resolve URLs automatically, without any extra code.

#### Filament conventions detected

The command probes all common Filament namespace patterns (v3, v4, v5):

```
App\Filament\Resources\{Model}Resource          (v3)
App\Filament\Resources\{Models}\{Model}Resource (v4 per-resource folder)
App\Filament\Admin\Resources\{Model}Resource    (v5 admin panel)
App\Filament\Admin\Resources\{Models}\{Model}Resource
App\Filament\Clusters\{Models}\Resources\{Model}Resource (v5 clusters)
```

Re-running the command is safe — it tops up only what's missing on partially-registered models. An existing `globalSearchUrl()` in the model is never overwritten.

> **Note:** The registration command rewrites the model file using a PHP pretty-printer, which normalises whitespace and formatting across the entire file. Commit your model file (or ensure it's clean) before running the command if you want a minimal diff.

Use `--dry-run` to preview the planned edits without touching files:

```bash
php artisan scoutify:searchable --dry-run
```

Use `--no-stubs` to skip injecting `globalSearchUrl()` (the trait's runtime cascade still applies):

```bash
php artisan scoutify:searchable --no-stubs
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

Prefix each command with the appropriate Artisan invocation for your environment:

| Environment | Prefix |
|---|---|
| Host (`php artisan serve`) | `php artisan` |
| Laravel Sail | `sail artisan` |
| Docker Compose (non-Sail) | `docker compose exec app php artisan` |

| Command | Description |
|---|---|
| `scoutify:install` | Install driver packages, publish config, and configure the search backend |
| `scoutify:doctor` | Verify driver configuration and backend connectivity |
| `scoutify:searchable` | Register models as globally searchable (injects `globalSearchUrl` stub) |
| `scoutify:searchable --no-stubs` | Register without injecting method stubs (URL resolved by trait cascade at runtime) |
| `scoutify:import` | Import all registered models into Scout index |
| `scoutify:flush` | Flush all registered models from Scout index |
| `scoutify:sync` | Flush then re-import (shortcut) |

## Updating

```bash
# Host
composer update matheusmarnt/scoutify

# Laravel Sail
./vendor/bin/sail composer update matheusmarnt/scoutify

# Docker Compose (non-Sail)
docker compose exec app composer update matheusmarnt/scoutify
```

After updating, check if the config file has new keys and merge them:

```bash
php artisan vendor:publish --tag=scoutify-config --force
```

> **Note:** `--force` overwrites your published config. Back it up first or diff manually against `vendor/matheusmarnt/scoutify/config/scoutify.php`.

Run `scoutify:doctor` to verify the setup is still healthy:

```bash
php artisan scoutify:doctor
```

If the new version changes Scout index structure, re-import:

```bash
php artisan scoutify:sync
```

## Testing

```bash
composer test          # Run test suite
composer test:coverage # Run with coverage (requires xdebug or pcov, min 90%)
```

## License

MIT — see [LICENSE](LICENSE.md).
