# Installation Guide

## Requirements

- PHP `^8.2`
- Laravel `^11.0 || ^12.0`
- Livewire `^3.0 || ^4.0`
- Tailwind CSS `^4.0`
- Laravel Scout `^11.1`

## 1. Install the package

```bash
composer require matheusmarnt/scoutify
```

## 2. Run the installer

```bash
php artisan scoutify:install
```

The installer prompts for a Scout driver (`meilisearch`, `algolia`, or `typesense`), installs the driver's Composer packages, publishes `config/scoutify.php` and `config/scout.php`, sets `SCOUT_DRIVER` in `.env`, and runs `scoutify:doctor` automatically.

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

> **Meilisearch search behaviour:** Meilisearch uses word-boundary prefix search. Substrings that are not word-prefixes (e.g. `"ano"` inside `"Mariano"`) return no results. Override `globalSearchBuilder()` on your model for custom matching, or switch to the `database` driver for `LIKE`-based substring search. See [Query customization](#query-customization) below.

### Meilisearch (Docker Compose — non-Sail)

The installer detects an existing `compose.yaml` (or `docker-compose.yml`) and generates a `compose.scoutify.yaml` override file at the project root with the Meilisearch service definition:

```bash
docker compose exec app php artisan scoutify:install
docker compose -f compose.yaml -f compose.scoutify.yaml up -d
docker compose exec app php artisan scoutify:doctor
```

Add `meilisearch` to your `app` service's `depends_on` in `compose.yaml`. The generated `compose.scoutify.yaml` is safe to commit.

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

### Typesense (Docker Compose — non-Sail)

```bash
docker compose exec app php artisan scoutify:install
docker compose -f compose.yaml -f compose.scoutify.yaml up -d
docker compose exec app php artisan scoutify:doctor
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

After editing, the command **automatically rebuilds the type manifest** so registered models appear in the UI without any additional steps.

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
public function globalSearchTitle(): string      { return $this->full_name; }
public function globalSearchSubtitle(): ?string  { return $this->email; }
public static function globalSearchGroup(): string  { return 'Team Members'; }
public static function globalSearchLabel(): string  { return 'Team Members'; }  // UI chip label
public static function globalSearchIcon(): string   { return 'heroicon-o-user'; }
public static function globalSearchColor(): string  { return 'blue'; }
```

> **`globalSearchSubtitle()` auto-discovery:** the trait automatically detects `description`, `subtitle`, `excerpt`, `summary`, `bio`, or `body` attributes. The value is **sanitized to plain text** (HTML tags stripped, entities decoded, whitespace collapsed) then truncated to 150 chars. CMS fields with HTML markup (`<p>`, `<strong>`, `<a>`, etc.) display cleanly in the result row without escaped tags. Override the method only when you need custom logic or a different attribute.

> **Overriding with HTML content:** when you override `globalSearchTitle()` or `globalSearchSubtitle()`, default sanitization is bypassed. If the returned value may contain HTML, call `Sanitizer::toPlainText()` explicitly:
>
> ```php
> use Matheusmarnt\Scoutify\Support\Sanitizer;
>
> public function globalSearchSubtitle(): ?string
> {
>     return Sanitizer::toPlainText($this->richTextBody, 150);
> }
> ```

**URL resolution cascade** (used both at registration time and at runtime):

| Priority | Condition | Generated stub |
|---|---|---|
| 1 | Filament resource exists | `UserResource::getUrl('view', ['record' => $this])` |
| 2 | Named route `{plural}.show` exists | `route('users.show', $this)` |
| 3 | Folio page `pages/users/[user].blade.php` exists | `url('/users/'.$this->getKey())` |
| 4 | None of the above | `url('/')` placeholder |

Flags: `--dry-run` (preview without writing), `--no-stubs` (skip injecting `globalSearchUrl`), `--all` (register all discovered models).

> **Note:** The registration command rewrites the model file using a PHP pretty-printer, which normalises whitespace and formatting across the entire file. Commit your model file (or ensure it's clean) before running the command if you want a minimal diff.

## 4. Import data

```bash
php artisan scoutify:import
```

Imports all discovered and configured models into the Scout index. No manual configuration needed — the command reads from both the auto-discovered type manifest and `config/scoutify.types`.

Import a specific model:

```bash
php artisan scoutify:import "App\Models\User"
```

## 5. Add to your layout

```blade
{{-- resources/views/layouts/app.blade.php --}}

{{-- Desktop trigger: pill with label + ⌘K badge, visible on lg+ --}}
<x-scoutify::gs.trigger class="hidden lg:inline-flex" />

{{-- Mobile trigger: 44×44 px icon-only button, hidden on lg+ --}}
<x-scoutify::gs.trigger-mobile />

{{-- ... rest of layout ... --}}

{{ $slot }}

{{-- Modal: must be at root level, AFTER $slot --}}
<livewire:scoutify::modal />

@livewireScripts
```

The desktop trigger renders a `⌘K` / `Ctrl+K` badge. The mobile trigger renders a 44×44 px magnifying-glass icon button meeting WCAG/HIG touch-target guidelines. The modal wires global keyboard shortcuts automatically.

> **You can use either or both triggers.** If you only need one, omit the other. Each trigger independently dispatches `scoutify:open` and is fully accessible.

> **⚠️ Modal placement is critical.** Place `<livewire:scoutify::modal />` at the **root of your layout**, outside any collapsible or conditionally-rendered container (sidebar, drawer, off-canvas nav). Livewire only initialises components that are in the DOM when the page loads — if the modal is inside a collapsed sidebar, it will not mount until the sidebar is opened, making the trigger appear broken.
>
> Recommended: place it directly after `{{ $slot }}`, before `@livewireScripts`.
>
> The trigger (`<x-scoutify::gs.trigger />`) has no such restriction and can go anywhere.

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

## Query Customization

Override `globalSearchBuilder()` on any model to apply custom filters, scopes, or driver-specific options:

```php
use Laravel\Scout\Builder;

public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    return $builder->where('published', true)->where('tenant_id', auth()->user()->tenant_id);
}
```

**Meilisearch infix matching** — Meilisearch only matches word prefixes by default. To support substring search (e.g. `"ano"` matching `"Mariano"`), configure `attributesToSearchOn` via the Meilisearch SDK and override the builder:

```php
use Laravel\Scout\Builder;

public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    // Requires the Meilisearch index to have 'attributesToSearchOn' configured
    // for the relevant fields. See Meilisearch docs on infix search settings.
    return $builder;
}
```

Alternatively, switch to the `database` driver for `LIKE`-based substring search (no external service required).

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

## Rebuilding the Type Manifest

Scoutify caches discovered model types at `bootstrap/cache/scoutify-types.php`. `scoutify:searchable` rebuilds it automatically. Rebuild manually if you add or remove the `Searchable` trait without using the command:

```bash
php artisan scoutify:rebuild
```

## Updating

```bash
composer update matheusmarnt/scoutify
php artisan vendor:publish --tag=scoutify-config --force
php artisan scoutify:rebuild
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

Checks driver configuration and backend connectivity. Warns if no types are registered (config + registry both empty), if `@livewireScripts` is missing from layouts, and if Meilisearch's word-boundary search may cause unexpected empty results. Exit code `0` = healthy, `1` = issue found (usable in CI health checks).
