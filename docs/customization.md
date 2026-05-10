# Fluent API Customization

All UI, theme, type registration, and slot customization is done via the fluent PHP API. Call in `AppServiceProvider::boot()` (or any service provider).

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

> **Full replacement, not merge.** Each setter replaces the entire class string of that element. When overriding, include every class you need — structural, spacing, color, and dark-mode variants. The defaults below are the starting point.

| Method | Element | Default classes |
|---|---|---|
| `dialogPanel(string)` | Outer positioning wrapper (no background) | `relative w-full md:max-w-2xl` |
| `dialogContent(string)` | Inner content card (background, shadow, rounded) | `flex max-h-[90dvh] min-h-0 flex-col overflow-hidden rounded-t-2xl bg-white pb-[env(safe-area-inset-bottom)] md:max-h-[80vh] md:rounded-xl md:shadow-2xl md:ring-1 md:ring-zinc-900/5 dark:bg-zinc-900 dark:md:ring-white/10` |
| `dialogScrim(string)` | Backdrop overlay | `absolute inset-0 bg-zinc-950/50` |
| `input(string)` | Search input field | `block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-scoutify-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden` |
| `trigger(string)` | Desktop trigger button | `group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200` |
| `triggerMobile(string)` | Mobile trigger button | `lg:hidden inline-flex size-11 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200` |
| `toggleActive(string)` | Active state of filter toggle switch | `bg-indigo-600 dark:bg-indigo-500` |
| `toggleInactive(string)` | Inactive state of filter toggle switch | `bg-zinc-200 dark:bg-zinc-700` |
| `accent(string)` | Modal wrapper — injects `--scoutify-accent` CSS custom property | No default override — package CSS defines the `scoutify-accent` token |
| `previewButton(string)` | Eye button on result rows (opens preview pane) — **appended** | `inline-flex size-7 shrink-0 items-center justify-center rounded-md text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-300` |
| `downloadButton(string)` | Download button on result rows and preview header — **appended** | `inline-flex size-7 shrink-0 items-center justify-center rounded-md text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-300` (result rows); preview header uses `size-8` variant |
| `previewBackButton(string)` | Back arrow button in preview pane header — **appended** | `inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent/40 dark:hover:bg-zinc-800 dark:hover:text-zinc-200` |
| `color(name, light, dark)` | Register a named custom color token | — |

### Customizing the modal background

`dialogPanel` is the outer positioning container — it has no background. To change the modal's background color, use `dialogContent()`, which controls the visible card (background, border-radius, shadow):

```php
// Change modal background only — keep all other structural classes
Scoutify::theme()
    ->dialogContent(
        'flex max-h-[90dvh] min-h-0 flex-col overflow-hidden rounded-t-2xl ' .
        'bg-slate-800 ' .  // ← your custom background
        'pb-[env(safe-area-inset-bottom)] md:max-h-[80vh] md:rounded-xl ' .
        'md:shadow-2xl md:ring-1 md:ring-zinc-900/5 dark:bg-slate-900 dark:md:ring-white/10'
    );
```

### Widening the modal

`dialogPanel` controls max-width. Override only the sizing — no background needed here:

```php
Scoutify::theme()
    ->dialogPanel('relative w-full md:max-w-4xl');
```

### Overriding the accent color

`accent()` accepts any CSS color value. It injects `--scoutify-accent` as an inline CSS custom property on the modal wrapper, which propagates to all child elements via cascade — no Tailwind safelist needed:

```php
Scoutify::theme()->accent('#7c3aed');
// or: ->accent('var(--color-violet-600)')  // Tailwind CSS 4 token
```

All focus rings, input borders, and highlighted elements reference `--scoutify-accent` through the package's `scoutify-accent` token.

### Customizing preview buttons

`previewButton()`, `downloadButton()`, and `previewBackButton()` **append** classes to the element's existing base classes — they do not replace them. Use them to add hover effects, backgrounds, or Tailwind overrides on top of the defaults:

```php
Scoutify::theme()
    ->previewButton('hover:bg-indigo-100 dark:hover:bg-indigo-900')
    ->downloadButton('hover:bg-emerald-100 dark:hover:bg-emerald-900')
    ->previewBackButton('hover:bg-zinc-200 dark:hover:bg-zinc-700');
```

### Custom color tokens

Register named color tokens that models can reference via `color: 'brand'` in `register()`:

```php
Scoutify::theme()->color('brand', 'bg-violet-100 text-violet-700', 'dark:bg-violet-900/60 dark:text-violet-200');
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
| `showIdleHint(bool)` | `true` | Prompt text paragraph in the idle state — does not hide the magnifying-glass icon or `recent-list` |

### Slots

| Slot key | Position | Behavior |
|---|---|---|
| `header-trailing` | After search input in modal header | Appended |
| `idle-extra` | After idle state (before user types) | Appended |
| `after-results` | After last result group | Appended |
| `empty-state` | When a non-blank query returns 0 results | **Replaces** default "No results" component; receives `$ctx->query` |

Slot keys accept either hyphen (`header-trailing`) or underscore (`header_trailing`) notation — both are equivalent.

```php
use Matheusmarnt\Scoutify\Support\SlotContext;

$ui->slot('after-results', 'search.footer');                             // Blade view name
$ui->slot('empty-state', function (SlotContext $ctx) { /* ... */ });     // Closure
$ui->slot('empty-state', \App\View\Components\SearchEmptyState::class);  // Component class
```

`SlotContext` properties: `$wire`, `$query`, `$results` (`Collection`), `$hasResults`, `$isIdle`. Use `$ctx->results->count()`, `->first()`, `->filter()`, etc.

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
    ->dialogPanel('relative w-full md:max-w-4xl')
    ->dialogContent(
        'flex max-h-[90dvh] min-h-0 flex-col overflow-hidden rounded-t-2xl ' .
        'bg-slate-800 pb-[env(safe-area-inset-bottom)] md:max-h-[80vh] md:rounded-xl ' .
        'md:shadow-2xl md:ring-1 md:ring-zinc-900/5 dark:bg-slate-900 dark:md:ring-white/10'
    )
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
