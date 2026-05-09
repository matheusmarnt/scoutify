---
title: Configuration Reference
description: Detailed reference for all configuration options in config/scoutify.php.
---

This page covers the `config/scoutify.php` options that remain in v2. UI, theme, type, and color customization moved to the [fluent PHP API](/scoutify/upgrading/v2).

### `debounce_ms`
- **Default**: `250`
- **Description**: Wait time in milliseconds before triggering a search after the user stops typing.

### `recents`
Configuration for the "Recent Searches" feature.
- `enabled`: (bool) Whether to track recent searches.
- `limit`: (int) Maximum number of items to keep.
- `storage`: (string) Storage driver (`'session'` is recommended).

### `discovery`
- **Description**: Paths where Scoutify should scan for models using the `Searchable` trait.
- **Paths**: Defaults to `[app_path('Models')]`.

### `preview`
File preview feature configuration.
- `enabled`: (bool) Toggle the preview pane feature.
- `route_prefix`: (string) URL prefix for preview routes (default `'scoutify/preview'`).
- `middleware`: (array) Middleware applied to preview routes.
- `ttl_seconds`: (int) Signed URL TTL in seconds (default `300`).
- `max_size_bytes`: (int) Maximum file size in bytes allowed for preview.
- `allowed_mimes`: (array) MIME types eligible for preview.
- `viewer_for_mime`: (array) Map MIME type patterns to custom Blade viewer views.
- `fallback_view`: (string) Blade view used when no viewer matches.

### `modal`
- `breakpoint_desktop`: (string) Tailwind breakpoint at which the modal switches from mobile to desktop layout (default `'md'`).

### `authorization`
- `default`: `'secure'` | `'permissive'` | `'gate-only'`
- `gate_ability`: The ability name used for Laravel policy checks (default `'view'`).

### `broadcast_events`
- **Description**: Customizable event names dispatched by the modal to the browser.
- **Defaults**: `scoutify:open`, `scoutify:opened`, `scoutify:closed`, `scoutify:remember`.

---

> **Removed in v2**: `icon_prefix`, `types`, `classes`, `colors`, and `modal.ui` are no longer config keys.
> Use `Scoutify::types()`, `Scoutify::theme()`, and `Scoutify::configureUi()` instead.
> See the [v2 upgrade guide](/scoutify/upgrading/v2).
