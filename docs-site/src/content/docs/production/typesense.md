---
title: Typesense
description: Production setup for Typesense with Scoutify.
---

Typesense is an open-source, fast, typo-tolerant search engine optimized for developer productivity.

## Environment Variables

```env
SCOUT_DRIVER=typesense
TYPESENSE_HOST=your-typesense-host
TYPESENSE_PORT=443
TYPESENSE_PROTOCOL=https
TYPESENSE_API_KEY=your-api-key
```

## Collection Schema

Typesense requires schemas to be declared before indexing. Configure this in `config/scout.php`:

```php
'typesense' => [
    'model-settings' => [
        \App\Models\User::class => [
            'collection-schema' => [
                'fields' => [
                    ['name' => 'name', 'type' => 'string'],
                    ['name' => 'email', 'type' => 'string'],
                ],
                'default_sorting_field' => 'created_at',
            ],
        ],
    ],
],
```

Apply the schema changes:

```bash
php artisan scout:sync-index-settings
php artisan scoutify:sync
```

## Self-hosted vs Cloud

| Feature | Self-hosted | Typesense Cloud |
|---|---|---|
| Maintenance | Manual | Automatic |
| Backups | Manual | Included |
| Scaling | Manual | One-click |

Typesense Cloud is recommended for production if you want a zero-maintenance experience.
