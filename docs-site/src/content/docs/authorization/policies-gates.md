---
title: Policies & Gates
description: Learn how Scoutify integrates with Laravel's built-in authorization features.
---

Scoutify uses Laravel's `Gate` facade and Eloquent `Policies` to control result visibility by default.

## Global Configuration

The default authorization mode can be changed in `config/scoutify.php`:

```php
'authorization' => [
    'default' => 'secure', // 'secure' | 'permissive' | 'gate-only'
    'gate_ability' => 'view',
],
```

## Authorization Modes

| Mode | Description |
|---|---|
| `secure` (default) | Guests denied. Auth users must pass `Gate::check($ability)` if a policy/gate exists, otherwise allowed. |
| `permissive` | All results are visible to everyone. No checks performed. |
| `gate-only` | Everyone (including guests) must pass `Gate::check($ability)`. Fails closed if gate/policy is missing. |

## How Gate Fallback Works

When a model doesn't implement `HasGlobalSearchVisibility`, Scoutify uses the `GlobalSearchAuthorizer`. 

If mode is `secure`, it checks for a registered policy for the model and the `view` ability. If no policy is found, it checks if a gate named `view` is defined. If neither exist, it allows authenticated users to see the result.

## Customizing the Ability

You can change the ability name used for these checks globally:

```php
'gate_ability' => 'search', // Checks Gate::check('search', $record)
```
