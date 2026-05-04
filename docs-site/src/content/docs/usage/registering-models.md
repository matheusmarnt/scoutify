---
title: Registering Models
description: Make your Eloquent models searchable in the global search modal.
---

## The `scoutify:searchable` command

The easiest way to register a model is using the provided Artisan command:

```bash
php artisan scoutify:searchable User
```

This command will:
1. Implement the `GloballySearchable` contract.
2. Add the `Searchable` trait.
3. Automatically resolve the URL for the search results.

## Manual Registration

You can also register models manually by adding the contract and trait yourself:

```php
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements GloballySearchable
{
    use Searchable;

    public function globalSearchUrl(): string
    {
        return route('articles.show', $this);
    }
}
```

## Customization Methods

You can override any of the following methods on your model to customize its appearance and behavior in the search results:

| Method | Default | Description |
|---|---|---|
| `globalSearchTitle()` | Model title/name | The main title of the result row. |
| `globalSearchSubtitle()` | Auto-discovered | The subtitle/description shown below the title. |
| `globalSearchUrl()` | Resolved | The destination URL when the result is clicked. |
| `globalSearchGroup()` | Class name | The group name used for result clustering. |
| `globalSearchLabel()` | Class name | The label shown in the filter chips. |
| `globalSearchIcon()` | `heroicon-o-` | The icon shown next to the result. |
| `globalSearchColor()` | `blue` | The color token used for the group chip. |

## Subtitle Auto-discovery

Scoutify automatically looks for common fields to use as a subtitle, such as `description`, `subtitle`, `excerpt`, `summary`, `bio`, or `body`. 

HTML content in these fields is automatically sanitized to plain text and truncated to 150 characters.

## Icon Resolution

Scoutify integrates with [Blade Icons](https://github.com/blade-ui-kit/blade-icons). You can use icons from any installed pack:

```php
public static function globalSearchIcon(): string
{
    return 'ri-customer-service-2-fill';
}
```

## Dry Run

Use the `--dry-run` flag to see what changes the `scoutify:searchable` command would make without actually modifying any files:

```bash
php artisan scoutify:searchable User --dry-run
```
