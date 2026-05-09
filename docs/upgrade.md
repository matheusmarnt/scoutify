# Upgrading to v2.0

Scoutify v2.0 replaces config-file UI customization with a fluent PHP API. This is a **hard breaking change** — any removed config key still present in `config/scoutify.php` will throw a `RuntimeException` on boot.

> **Warning:** If your `config/scoutify.php` still contains legacy keys (`icon_prefix`, `types`, `classes`, `colors`, `modal.ui`), running `composer update` will crash during the `post-update-cmd` step with a `RuntimeException` and no further guidance. Complete steps 1–2 below before updating.

## What changed

The following `config/scoutify.php` keys **no longer exist**:

| Removed key | Replacement |
|---|---|
| `icon_prefix` | `Scoutify::types()->iconPrefix()` |
| `types` | `Scoutify::types()->register()` |
| `classes` | `Scoutify::theme()->…()` |
| `colors` | `Scoutify::theme()->color()` |
| `modal.ui` | `Scoutify::configureUi()` |

Config keys that remain unchanged: `debounce_ms`, `recents`, `discovery`, `preview`, `modal.breakpoint_desktop`, `authorization`, `broadcast_events`.

## Upgrade sequence

### 1. Bump the version constraint

Composer's caret (`^1.x`) blocks major-version upgrades — v2 will never be installed until you change the constraint. Edit `composer.json` manually:

```json
"matheusmarnt/scoutify": "^2.1"
```

### 2. Remove legacy config keys

**Before running `composer update`**, remove the legacy keys from `config/scoutify.php`. The safest approach is to delete the file entirely — you will re-publish it at step 4:

```bash
rm config/scoutify.php
```

If you have customized keys that survive into v2 (`debounce_ms`, `recents`, `discovery`, `preview`, `modal.breakpoint_desktop`, `authorization`, `broadcast_events`), manually remove only the legacy keys (`icon_prefix`, `types`, `classes`, `colors`, `modal.ui`) instead of deleting the file.

### 3. Run `composer update`

```bash
composer update matheusmarnt/scoutify -W
```

### 4. Re-publish config

```bash
php artisan vendor:publish --tag=scoutify-config --force
```

### 5. Migrate customizations

Move any values you removed in step 2 to `AppServiceProvider::boot()` using the fluent API. See the sections below.

---

## Migrating customizations

### Move `types`

**Before:**

```php
// config/scoutify.php
'types' => [
    \App\Models\User::class => ['label' => 'Users', 'icon' => 'heroicon-o-user',          'color' => 'indigo'],
    \App\Models\Post::class => ['label' => 'Posts', 'icon' => 'heroicon-o-document-text', 'color' => 'blue'],
],
```

**After** — call in `boot()` of `App\Providers\AppServiceProvider` (or any service provider):

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;

public function boot(): void
{
    Scoutify::types()
        ->register(\App\Models\User::class, label: 'Users', icon: 'heroicon-o-user',          color: 'indigo')
        ->register(\App\Models\Post::class, label: 'Posts', icon: 'heroicon-o-document-text', color: 'blue');
}
```

`register()` accepts the same fields (`label`, `icon`, `color`) as the old array format.

### Move `icon_prefix`

**Before:**

```php
// config/scoutify.php
'icon_prefix' => 'ri-',
```

**After:**

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;

public function boot(): void
{
    Scoutify::types()->iconPrefix('ri-');
}
```

### Move `classes`

**Before:**

```php
// config/scoutify.php
'classes' => [
    'dialog_panel'    => 'relative bg-white dark:bg-zinc-900 ...',
    'dialog_scrim'    => 'fixed inset-0 ...',
    'input'           => 'w-full ...',
    'trigger'         => 'flex items-center ...',
    'toggle_active'   => 'bg-blue-100 text-blue-700 ...',
    'toggle_inactive' => 'text-gray-500 ...',
],
```

**After:**

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;

public function boot(): void
{
    Scoutify::theme()
        ->dialogPanel('relative bg-white dark:bg-zinc-900 ...')
        ->dialogScrim('fixed inset-0 ...')
        ->input('w-full ...')
        ->trigger('flex items-center ...')
        ->toggleActive('bg-blue-100 text-blue-700 ...')
        ->toggleInactive('text-gray-500 ...');
}
```

### Move `colors` (custom color tokens)

**Before:**

```php
// config/scoutify.php
'colors' => [
    'brand' => ['light' => 'bg-blue-100 text-blue-700', 'dark' => 'dark:bg-blue-900/60 dark:text-blue-200'],
],
```

**After:**

```php
Scoutify::theme()->color('brand', 'bg-blue-100 text-blue-700', 'dark:bg-blue-900/60 dark:text-blue-200');
```

### Move `modal.ui` visibility flags

**Before:**

```php
// config/scoutify.php
'modal' => [
    'ui' => [
        'show_type_chips' => false,
        'show_hint_bar'   => false,
    ],
],
```

**After:**

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Support\UiConfig;

public function boot(): void
{
    Scoutify::configureUi(function (UiConfig $ui) {
        $ui->showTypeChips(false)
           ->showHintBar(false);
    });
}
```

Available `UiConfig` flags:

| Method | Default | Description |
|---|---|---|
| `showTypeChips(bool)` | `true` | Toggle the model-type filter chips |
| `showToggleOnlyActive(bool)` | `true` | Toggle the "Active only" toggle |
| `showToggleIncludeTrashed(bool)` | `true` | Toggle the "Include trashed" toggle |
| `showHintBar(bool)` | `true` | Toggle the keyboard-shortcut hint bar |
| `showIdleHint(bool)` | `true` | Toggle the idle-state hint |

---

## Complete example

```php
<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Support\UiConfig;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Scoutify::types()
            ->iconPrefix('heroicon-o-')
            ->register(User::class, label: 'Users', icon: 'user',          color: 'indigo')
            ->register(Post::class, label: 'Posts', icon: 'document-text', color: 'blue');

        Scoutify::theme()
            ->dialogPanel('relative bg-white dark:bg-zinc-900 rounded-xl shadow-2xl ring-1 ring-black/5');

        Scoutify::configureUi(function (UiConfig $ui) {
            $ui->showHintBar(false);
        });
    }
}
```

## Tailwind safelist

If you added custom colors via `Scoutify::theme()->color()`, add their classes to your Tailwind source so they are included in the CSS build:

```css
/* resources/css/app.css */
@source inline("bg-brand-100 text-brand-700 dark:bg-brand-900/60 dark:text-brand-200");
```
