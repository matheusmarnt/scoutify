---
title: Visibility Rules
description: Control which users can see which search results using a fluent API.
---

import { Aside } from '@astrojs/starlight/components';

Scoutify is **secure-by-default**. Since v1.12.0, visibility rules are used to control access to search results.

## Why Visibility Rules?

By default, guests cannot see search results, and authenticated users can only see results they are authorized to view via policies. Visibility rules allow you to define these permissions fluently on the model itself.

## Implementing `HasGlobalSearchVisibility`

To customize visibility, implement the `HasGlobalSearchVisibility` contract and the `globalSearchVisibility()` method:

```php
use Matheusmarnt\Scoutify\Authorization\VisibilityRule;
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchVisibility;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasGlobalSearchVisibility
{
    public function globalSearchVisibility(): VisibilityRule
    {
        return VisibilityRule::make()
            ->visibleToGuests()
            ->orWhenAuthenticated()
                ->policy('view');
    }
}
```

## Fluent Builder Reference

| Method | Argument | Effect |
|---|---|---|
| `visibleToGuests()` | — | Allow unauthenticated viewers. |
| `orWhenAuthenticated()` | — | Chain clause for authenticated users. |
| `policy(string $ability)` | ability name | Run `Gate::check($ability, $record)`. |
| `permission(string\|array $perm)` | Spatie permission(s) | `$user->hasPermissionTo($perm)`. |
| `role(string\|array $role)` | Spatie role(s) | `$user->hasRole($role)`. |
| `attribute(string $attr, mixed $val)` | column + expected | Compare model column (default `true`). |
| `using(callable $fn)` | `fn($rec, $user)` | Custom resolver logic. |

## Combining Rules

Rules are combined using logical OR by default. You can change this behavior to require all rules to pass:

```php
return VisibilityRule::make()
    ->role('admin')
    ->attribute('is_active', true)
    ->mode(VisibilityMode::All); // Logical AND
```

## Worked Example

A `Post` model that is visible to guests if published, or to the author if it's a draft:

```php
public function globalSearchVisibility(): VisibilityRule
{
    return VisibilityRule::make()
        ->visibleToGuests()
            ->attribute('status', 'published')
        ->orWhenAuthenticated()
            ->using(fn ($record, $user) => $user->id === $record->author_id);
}
```

<Aside type="tip">
  Visibility rules are evaluated in real-time when results are aggregated, ensuring your search results are always privacy-compliant.
</Aside>
