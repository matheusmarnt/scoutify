---
title: Configuration Reference
description: Detailed reference for all configuration options in config/scoutify.php.
---

This page provides a detailed breakdown of all settings available in `config/scoutify.php`.

### `icon_prefix`
- **Default**: `'heroicon-o-'`
- **Description**: The default prefix prepended to icon names if no pack prefix is detected. Compatible with any [Blade Icons](https://github.com/blade-ui-kit/blade-icons) pack.

### `recents`
Configuration for the "Recent Searches" feature.
- `enabled`: (bool) Whether to track recent searches.
- `limit`: (int) Maximum number of items to keep.
- `storage`: (string) Storage driver (`'session'` is recommended).

### `debounce_ms`
- **Default**: `250`
- **Description**: Wait time in milliseconds before triggering a search after the user stops typing.

### `types`
- **Description**: Manual registration of model types. Use this if you don't want to use auto-discovery.
- **Example**: 
  ```php
  'App\Models\User' => ['icon' => 'user', 'color' => 'indigo', 'label' => 'Users'],
  ```

### `classes`
- **Description**: Custom CSS classes for every UI element in the search modal. Use this to style the modal with your own Tailwind or CSS tokens.

### `discovery`
- **Description**: Paths where Scoutify should scan for models using the `Searchable` trait.
- **Paths**: Defaults to `[app_path('Models')]`.

### `authorization`
- `default`: `'secure'` | `'permissive'` | `'gate-only'`
- `gate_ability`: The ability name used for Laravel policy checks (defaults to `'view'`).

### `broadcast_events`
- **Description**: Customizable event names dispatched by the modal to the browser.
- **Defaults**: `scoutify:open`, `scoutify:opened`, `scoutify:closed`, `scoutify:remember`.
