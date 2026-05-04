---
title: Contracts
description: Technical reference for Scoutify interface contracts.
---

Scoutify uses two main interfaces to integrate with your Eloquent models.

## `GloballySearchable`

This contract is required for any model you want to appear in the search results.

```php
namespace Matheusmarnt\Scoutify\Contracts;

interface GloballySearchable
{
    public function globalSearchTitle(): string;
    public function globalSearchSubtitle(): ?string;
    public function globalSearchUrl(): string;
}
```

### Methods
- `globalSearchTitle()`: Returns the string displayed as the main title.
- `globalSearchSubtitle()`: Returns a secondary string displayed below the title.
- `globalSearchUrl()`: Returns the absolute URL the user is redirected to upon clicking.

## `HasGlobalSearchVisibility`

This optional contract allows you to define custom visibility rules for a model.

```php
namespace Matheusmarnt\Scoutify\Contracts;

use Matheusmarnt\Scoutify\Authorization\VisibilityRule;

interface HasGlobalSearchVisibility
{
    public function globalSearchVisibility(): VisibilityRule;
}
```

### Methods
- `globalSearchVisibility()`: Returns a `VisibilityRule` instance using the fluent builder.

## Trait Helpers

The `Matheusmarnt\Scoutify\Concerns\Searchable` trait provides default implementations for all methods in `GloballySearchable`, including auto-discovery of titles and subtitles.
