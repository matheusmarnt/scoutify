# Installation Guide

## Requirements

- PHP `^8.2`
- Laravel `^11.0 || ^12.0`
- Livewire `^3.0 || ^4.0`
- Tailwind CSS `^4.0`
- Laravel Scout `^10.0`

## 1. Install the package

```bash
composer require matheusmarnt/scoutify
```

## 2. Run the installer

```bash
php artisan scoutify:install
```

The installer prompts for a Scout driver (`meilisearch`, `algolia`, or `typesense`), installs the driver's Composer packages, publishes `config/scoutify.php`, sets `SCOUT_DRIVER` in `.env`, and runs `scoutify:doctor` automatically.

> **Environment-specific invocations**
>
> | Environment | Artisan invocation |
> |---|---|
> | `php artisan serve` (host) | `php artisan <command>` |
> | Laravel Sail | `./vendor/bin/sail artisan <command>` |
> | Docker Compose (non-Sail) | `docker compose exec app php artisan <command>` |

### Meilisearch (Sail)

```bash
./vendor/bin/sail artisan scoutify:install
./vendor/bin/sail down && ./vendor/bin/sail up -d
./vendor/bin/sail artisan scoutify:doctor
```

The installer detects Sail, runs `sail:add meilisearch`, and sets `MEILISEARCH_HOST=http://meilisearch:7700` in `.env`.

### Meilisearch (host)

```bash
# Start Meilisearch locally first
docker run -d --name meilisearch -p 7700:7700 getmeili/meilisearch:latest

php artisan scoutify:install
php artisan scoutify:doctor
```

### Typesense (Sail)

```bash
./vendor/bin/sail artisan scoutify:install
./vendor/bin/sail down && ./vendor/bin/sail up -d
./vendor/bin/sail artisan scoutify:doctor
```

### Typesense (host)

```bash
docker run -d --name typesense -p 8108:8108 \
  -v $(pwd)/typesense_data:/data \
  typesense/typesense:latest \
  --data-dir /data --api-key=xyz --enable-cors

php artisan scoutify:install
php artisan scoutify:doctor
```

### Algolia (any environment)

Algolia is cloud-hosted — no local service needed.

```bash
php artisan scoutify:install
# Fill in ALGOLIA_APP_ID and ALGOLIA_SECRET in .env
php artisan scoutify:doctor
```

## 3. Register models

```bash
php artisan scoutify:searchable
```

Discovers Eloquent models under `app/Models/`, prompts which to register, and automatically edits each chosen model file to:

1. Import `Matheusmarnt\Scoutify\Concerns\Searchable` and `Matheusmarnt\Scoutify\Contracts\GloballySearchable`
2. Add `implements GloballySearchable` to the class declaration
3. Insert `use Searchable;` as the first statement in the class body
4. Inject a concrete `globalSearchUrl()` method (auto-resolved via cascade)

**Example — User model with a Filament resource:**

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

**Override trait defaults on the model:**

```php
public function globalSearchTitle(): string     { return $this->full_name; }
public function globalSearchSubtitle(): ?string { return $this->email; }
public static function globalSearchGroup(): string { return 'Team Members'; }
public static function globalSearchIcon(): string  { return 'heroicon-o-user'; }
public static function globalSearchColor(): string { return 'blue'; }
```

**URL resolution cascade** (used both at registration time and at runtime):

| Priority | Condition | Generated stub |
|---|---|---|
| 1 | Filament resource exists | `UserResource::getUrl('view', ['record' => $this])` |
| 2 | Named route `{plural}.show` exists | `route('users.show', $this)` |
| 3 | Folio page `pages/users/[user].blade.php` exists | `url('/users/'.$this->getKey())` |
| 4 | None of the above | `url('/')` placeholder |

Flags: `--dry-run` (preview without writing), `--no-stubs` (skip injecting `globalSearchUrl`), `--all` (register all discovered models).

## 4. Import data

```bash
php artisan scoutify:import
```

## 5. Add to your layout

```blade
{{-- resources/views/layouts/app.blade.php --}}
<x-scoutify::gs.trigger />
<livewire:scoutify::modal />
```

The trigger renders a `⌘K` / `Ctrl+K` badge. The modal wires global keyboard shortcuts automatically.

Dispatch from any element:

```blade
<button x-on:click="$dispatch('scoutify:open')">Search</button>
```

## 6. Tailwind CSS v4

`scoutify:install` automatically adds the Scoutify CSS partial to `resources/css/app.css`. To add it manually:

```css
@import 'tailwindcss';
@import "../../vendor/matheusmarnt/scoutify/resources/css/scoutify.css";
```

## 7. Livewire scripts

Result rows use `wire:navigate`. Your layout must include `@livewireScripts`:

```blade
{{-- usually already present in your layout --}}
@livewireScripts
```

Without it, clicks fall back to full-page reloads.

## Customization

### CSS class overrides

All UI classes are configurable via `config/scoutify.php`:

```php
'classes' => [
    'trigger'         => 'flex items-center gap-2 ...',
    'dialog_scrim'    => 'fixed inset-0 ...',
    'dialog_panel'    => 'relative bg-white ...',
    'input'           => 'w-full border-0 ...',
    'toggle_active'   => 'bg-blue-100 text-blue-700 ...',
    'toggle_inactive' => 'text-gray-500 ...',
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

## Updating

```bash
composer update matheusmarnt/scoutify
php artisan vendor:publish --tag=scoutify-config --force
php artisan scoutify:doctor
```

If the new version changes Scout index structure:

```bash
php artisan scoutify:sync
```

## Diagnostics

```bash
php artisan scoutify:doctor
```

Checks driver configuration and backend connectivity. Exit code `0` = healthy, `1` = issue found (usable in CI health checks).
