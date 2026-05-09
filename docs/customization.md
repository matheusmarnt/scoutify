# Fluent API Customization

All UI, theme, type registration, and slot customisation is done via the fluent PHP API. Call in `AppServiceProvider::boot()` (or any service provider).

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Support\UiConfig;

public function boot(): void
{
    Scoutify::types()-> /* ... */ ;
    Scoutify::theme()-> /* ... */ ;
    Scoutify::configureUi(function (UiConfig $ui) { /* ... */ });
}
```

---

## `Scoutify::types()` — Type Registration

### `register()`

```php
Scoutify::types()->register(
    \App\Models\User::class,
    label: 'Users',
    icon:  'user',     // short name — prefix applied automatically
    color: 'indigo',   // Tailwind color name or custom token
);
```

| Parameter | Default | Description |
|---|---|---|
| `$class` | — | Fully-qualified model class |
| `label` | `''` | Display name in filter chip and group header |
| `icon` | `''` | Icon identifier (short name or full Blade Icons name) |
| `color` | `'zinc'` | Accent color — Tailwind name or custom token |

Chain calls to register multiple models:

```php
Scoutify::types()
    ->register(\App\Models\User::class,    label: 'Users',    icon: 'user',          color: 'indigo')
    ->register(\App\Models\Post::class,    label: 'Posts',    icon: 'document-text', color: 'sky')
    ->register(\App\Models\Product::class, label: 'Products', icon: 'shopping-bag',  color: 'emerald');
```

### `iconPrefix()`

Set a global prefix prepended to short icon names:

```php
Scoutify::types()->iconPrefix('heroicon-o-');
// 'user' → 'heroicon-o-user'
```

---

## `Scoutify::theme()` — CSS Class Overrides

| Method | What it styles |
|---|---|
| `dialogPanel(string)` | Modal dialog container |
| `dialogScrim(string)` | Backdrop overlay |
| `input(string)` | Search input field |
| `trigger(string)` | Desktop trigger button |
| `triggerMobile(string)` | Mobile trigger button |
| `toggleActive(string)` | Active state of filter chips |
| `toggleInactive(string)` | Inactive state of filter chips |
| `accent(string)` | Primary accent classes |
| `color(name, light, dark)` | Register a named custom color token |

```php
Scoutify::theme()
    ->dialogPanel('relative bg-white dark:bg-zinc-900 rounded-xl shadow-2xl ring-1 ring-black/5')
    ->color('brand', 'bg-violet-100 text-violet-700', 'dark:bg-violet-900/60 dark:text-violet-200');
```

Custom color classes must be in your Tailwind build:

```css
/* resources/css/app.css */
@source inline("bg-violet-100 text-violet-700 dark:bg-violet-900/60 dark:text-violet-200");
```

---

## `Scoutify::configureUi()` — Visibility Flags & Slots

```php
Scoutify::configureUi(function (UiConfig $ui) {
    $ui->showTypeChips(false)
       ->showHintBar(false);
});
```

### Visibility flags

| Method | Default | Description |
|---|---|---|
| `showTypeChips(bool)` | `true` | Model-type filter chips |
| `showToggleOnlyActive(bool)` | `true` | "Active only" toggle |
| `showToggleIncludeTrashed(bool)` | `true` | "Include trashed" toggle |
| `showHintBar(bool)` | `true` | Keyboard-shortcut hint bar |
| `showIdleHint(bool)` | `true` | Idle-state hint |

### Slots

```php
use Matheusmarnt\Scoutify\Support\SlotContext;

$ui->slot('after-results', 'search.footer');                             // Blade view name
$ui->slot('empty-state', function (SlotContext $ctx) { /* ... */ });     // Closure
$ui->slot('empty-state', \App\View\Components\SearchEmptyState::class);  // Component class
```

`SlotContext` properties: `$wire`, `$query`, `$results`, `$hasResults`, `$isIdle`.

---

## Complete example

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Support\SlotContext;
use Matheusmarnt\Scoutify\Support\UiConfig;

Scoutify::types()
    ->iconPrefix('heroicon-o-')
    ->register(\App\Models\User::class,  label: 'Users',  icon: 'user',          color: 'indigo')
    ->register(\App\Models\Post::class,  label: 'Posts',  icon: 'document-text', color: 'sky')
    ->register(\App\Models\Order::class, label: 'Orders', icon: 'shopping-cart', color: 'brand');

Scoutify::theme()
    ->dialogPanel('relative bg-white dark:bg-zinc-900 rounded-xl shadow-2xl ring-1 ring-black/5')
    ->color('brand', 'bg-violet-100 text-violet-700', 'dark:bg-violet-900/60 dark:text-violet-200');

Scoutify::configureUi(function (UiConfig $ui) {
    $ui->showHintBar(false)
       ->slot('empty-state', function (SlotContext $ctx) {
           return new \Illuminate\Support\HtmlString(
               "<p class=\"text-center text-sm text-gray-400 py-6\">No results for &ldquo;{$ctx->query}&rdquo;.</p>"
           );
       });
});
```
