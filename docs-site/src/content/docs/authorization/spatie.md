---
title: Spatie Integration
description: Use Spatie's Laravel Permission package to control search visibility.
---

Scoutify provides first-class support for `spatie/laravel-permission` to define visibility rules based on roles and permissions.

## Optional Dependency

Spatie is an optional dependency. You must install it to use the role and permission rules:

```bash
composer require spatie/laravel-permission
```

## Detection Mechanism

Scoutify uses `method_exists` to detect if your User model supports Spatie methods. It never imports Spatie classes directly, ensuring your application remains lightweight if you don't use it.

## `permission()` Rule

Grant visibility to users with specific permissions:

```php
// Single permission
->permission('view-articles')

// Multiple permissions (logical OR)
->permission(['edit-articles', 'view-articles'])
```

## `role()` Rule

Grant visibility to users with specific roles:

```php
// Single role
->role('admin')

// Multiple roles (logical OR)
->role(['manager', 'editor'])
```

## Limitations

- Wildcard permissions are supported only if your Spatie configuration has them enabled.
- The rule must match the Spatie guard configured for your application.
- Scoutify fails closed (denies access) if Spatie methods are missing when these rules are evaluated.
