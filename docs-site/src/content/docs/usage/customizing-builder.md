---
title: Customizing Builder
description: Apply custom filters and scopes to your global search queries.
---

import { Aside } from '@astrojs/starlight/components';

You can customize the Scout query for each model by overriding the `globalSearchBuilder()` method.

## Override `globalSearchBuilder()`

Use this method to apply where clauses, scopes, or other query modifications:

```php
use Laravel\Scout\Builder;

public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    return $builder->where('published', true);
}
```

## Meilisearch Word-boundary Caveat

Meilisearch uses word-prefix matching. Substrings that don't start at a word boundary (e.g., "ano" in "Mariano") won't match.

If you need infix/substring matching, you can configure `attributesToSearchOn` in Meilisearch or switch to the `database` driver.

## Algolia-specific Tuning

For Algolia, you can pass additional parameters to the search:

```php
public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    return $builder->with([
        'aroundLatLng' => '40.71, -74.01',
    ]);
}
```

## Database Driver Examples

The database driver uses `LIKE` queries, which support infix matching by default:

```php
public function globalSearchBuilder(Builder $builder, string $query): Builder
{
    return $builder->where('active', true);
}
```

<Aside type="note">
  The `database` driver is excellent for development or small datasets where you don't want to manage an external search service.
</Aside>
