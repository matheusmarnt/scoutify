---
title: Database
description: Use your application's database as a search engine for small datasets.
---

The Database driver is the simplest way to get started with Scoutify as it requires no external dependencies.

## When to use

- Datasets under ~50 000 records.
- Staging environments or local development.
- Applications where typo tolerance is not a priority.

## Configuration

Set the driver in your `.env` file:

```env
SCOUT_DRIVER=database
```

No additional configuration is needed in `config/scout.php` for basic usage.

## SQL Index Recommendations

For better performance on larger datasets, add a full-text index to your searchable columns:

```php
// In your migration
$table->fullText(['title', 'body']);
```

## Limitations

| Feature | Database | Meilisearch / Algolia |
|---|---|---|
| Typo Tolerance | No | Yes |
| Relevance Ranking | Basic | Advanced |
| Max Records | ~50k | Millions |
| Performance | Variable | Consistent |

For large-scale production applications, we recommend moving to Meilisearch or Typesense.
