# Upgrading Guide

## v1.x → v2.0.0

v2.0 replaces config-file UI customisation with a fluent PHP API. See the full migration guide at <https://matheusmarnt.github.io/scoutify/upgrading/v2>.

**Removed `config/scoutify.php` keys** (boot throws `RuntimeException` if present):

| Removed key | Replacement |
|---|---|
| `icon_prefix` | `Scoutify::types()->iconPrefix()` |
| `types` | `Scoutify::types()->register()` |
| `classes` | `Scoutify::theme()->…()` |
| `colors` | `Scoutify::theme()->color()` |
| `modal.ui` | `Scoutify::configureUi()` |

**Migration:**

```bash
php artisan vendor:publish --tag=scoutify-config --force
```

Then register types and configure the theme in `App\Providers\AppServiceProvider::boot()`:

```php
use Matheusmarnt\Scoutify\Facades\Scoutify;
use Matheusmarnt\Scoutify\Support\UiConfig;

public function boot(): void
{
    Scoutify::types()
        ->iconPrefix('heroicon-o-')
        ->register(\App\Models\User::class, label: 'Users', icon: 'user', color: 'indigo');

    Scoutify::theme()
        ->dialogPanel('relative bg-white dark:bg-zinc-900 ...');

    Scoutify::configureUi(fn (UiConfig $ui) => $ui->showHintBar(false));
}
```

## Unreleased → v0.1.0

Initial release. No upgrade path needed.
