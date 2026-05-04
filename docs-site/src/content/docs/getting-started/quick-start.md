---
title: Quick Start
description: Get Scoutify up and running in just a few minutes.
---

import { Tabs, TabItem } from '@astrojs/starlight/components';

## Install in 3 commands

```bash
composer require matheusmarnt/scoutify
php artisan scoutify:install
php artisan scoutify:doctor
```

## Register your first model

Pick an Eloquent model (e.g., `User`) and register it for global search:

```bash
php artisan scoutify:searchable User
```

This will automatically add the necessary trait and contract to your model.

## Import existing data

Populate your search index with your existing records:

```bash
php artisan scoutify:import
```

## Drop the trigger in a layout

Place the trigger component in your navigation or header:

<Tabs>
  <TabItem label="Blade">
    ```blade
    {{-- Desktop trigger --}}
    <x-scoutify::gs.trigger class="hidden lg:inline-flex" />

    {{-- Mobile trigger --}}
    <x-scoutify::gs.trigger-mobile class="lg:hidden" />
    ```
  </TabItem>
  <TabItem label="Livewire Volt">
    ```blade
    <x-scoutify::gs.trigger />
    ```
  </TabItem>
</Tabs>

## Open with ⌘K

Refresh your browser and press `⌘K` (or `Ctrl+K`) to open the search modal. Start typing to see results across your registered models.
