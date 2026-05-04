---
title: Artisan Commands
description: Reference for all Scoutify Artisan commands.
---

Scoutify includes several commands to help you manage your search index and registered models.

### `scoutify:install`

Initializes Scoutify in your project.

- **Purpose**: Prompts for driver, installs packages, and publishes config.
- **Signature**: `php artisan scoutify:install`
- **Options**:
  - `--driver=...`: Specify driver non-interactively.
  - `--no-interaction`: Skip prompts.

### `scoutify:searchable`

Makes Eloquent models globally searchable.

- **Purpose**: Edits model files to implement contracts and traits.
- **Signature**: `php artisan scoutify:searchable {model?}`
- **Options**:
  - `--all`: Register all discovered models.
  - `--dry-run`: Preview changes without writing to files.
  - `--no-stubs`: Skip injecting the `globalSearchUrl` method.

### `scoutify:import`

Imports models into the Scout index.

- **Purpose**: Populates the search backend with existing database records.
- **Signature**: `php artisan scoutify:import {model?}`
- **Arguments**:
  - `model`: Optional class name of a specific model to import.

### `scoutify:flush`

Removes models from the search index.

- **Purpose**: Wipes all records of a model from the search backend.
- **Signature**: `php artisan scoutify:flush {model?}`

### `scoutify:sync`

Wipes and re-imports models.

- **Purpose**: Ensures the index is perfectly in sync with the database.
- **Signature**: `php artisan scoutify:sync {model?}`

### `scoutify:rebuild`

Rebuilds the type manifest.

- **Purpose**: Scans your models and updates the discovery cache.
- **Signature**: `php artisan scoutify:rebuild`

### `scoutify:doctor`

Diagnoses your Scoutify installation.

- **Purpose**: Checks driver config, connectivity, and common pitfalls.
- **Signature**: `php artisan scoutify:doctor`
- **Exit Codes**: `0` for healthy, `1` for issues found.
