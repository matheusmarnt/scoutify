# Production Deployment

## Transversal settings (all drivers)

### Queue workers

Always enable queue-based indexing in production to avoid blocking web requests:

```env
SCOUT_QUEUE=true
```

Run a queue worker (or configure Horizon):

```bash
php artisan queue:work --queue=default
```

Without `SCOUT_QUEUE=true`, every model save triggers a synchronous HTTP call to the search backend.

### Initial import

After deploying for the first time (or after rebuilding indexes):

```bash
php artisan scoutify:import
```

To wipe and rebuild:

```bash
php artisan scoutify:sync
```

### Health check

```bash
php artisan scoutify:doctor
```

Exit code `0` = healthy. Wire into your CI pipeline or uptime monitor.

---

## Meilisearch

### Environment variables

```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=https://your-meilisearch-host:7700
MEILISEARCH_KEY=your-master-or-search-key
```

Use a **search-only API key** (never the master key) for the `MEILISEARCH_KEY` exposed to your application.

### Index settings

Meilisearch requires filterable and sortable attributes to be declared before indexing. Configure in `config/scout.php` or via Meilisearch's API:

```php
// config/scout.php
'meilisearch' => [
    'host' => env('MEILISEARCH_HOST'),
    'key'  => env('MEILISEARCH_KEY'),
    'index-settings' => [
        \App\Models\User::class => [
            'filterableAttributes' => ['deleted_at', 'is_active'],
            'sortableAttributes'   => ['name', 'created_at'],
            'rankingRules'         => ['words', 'typo', 'proximity', 'attribute', 'sort', 'exactness'],
        ],
    ],
],
```

Apply settings (re-apply after changing):

```bash
php artisan scout:sync-index-settings
```

### HTTPS + API key rotation

1. Run Meilisearch behind a TLS-terminating reverse proxy (nginx, Caddy).
2. Generate a dedicated search key with limited permissions via Meilisearch's `/keys` endpoint — never expose the master key.
3. Rotate keys via Meilisearch API and update `MEILISEARCH_KEY` in `.env` without downtime.

### Self-hosted recommended settings

```bash
# Production: set a master key, persist data volume
docker run -d --name meilisearch \
  -p 7700:7700 \
  -e MEILI_MASTER_KEY=your-strong-master-key \
  -e MEILI_ENV=production \
  -v $(pwd)/meili_data:/meili_data \
  getmeili/meilisearch:latest
```

---

## Typesense

### Environment variables

```env
SCOUT_DRIVER=typesense
TYPESENSE_HOST=your-typesense-host
TYPESENSE_PORT=443
TYPESENSE_PROTOCOL=https
TYPESENSE_API_KEY=your-api-key
```

For **Typesense Cloud**, the host is provided in your cluster dashboard (format: `xxx.a1.typesense.net`).

### Collection schema

Typesense requires schemas declared before indexing. In `config/scout.php`:

```php
'typesense' => [
    'client-settings' => [
        'nodes' => [[
            'host'     => env('TYPESENSE_HOST'),
            'port'     => env('TYPESENSE_PORT', '8108'),
            'protocol' => env('TYPESENSE_PROTOCOL', 'https'),
        ]],
        'api_key' => env('TYPESENSE_API_KEY'),
    ],
    'model-settings' => [
        \App\Models\User::class => [
            'collection-schema' => [
                'fields' => [
                    ['name' => 'name',       'type' => 'string'],
                    ['name' => 'email',      'type' => 'string', 'optional' => true],
                    ['name' => 'created_at', 'type' => 'int64'],
                ],
                'default_sorting_field' => 'created_at',
            ],
            'search-parameters' => [
                'query_by' => 'name,email',
            ],
        ],
    ],
],
```

### Self-hosted vs Typesense Cloud

| | Self-hosted | Typesense Cloud |
|---|---|---|
| Cost | Infra only | Usage-based |
| TLS | Configure via reverse proxy | Included |
| Scaling | Manual | Automatic |
| Backups | Manual | Included |

Self-hosted: run behind nginx/Caddy for TLS, use `--enable-cors` only if browser-direct access is needed.

---

## Algolia

### Environment variables

```env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your-app-id
ALGOLIA_SECRET=your-admin-api-key
```

### Secured API keys (recommended for production)

Never expose the admin API key on the client side. Generate a secured (scoped) API key server-side:

```php
$client = \Algolia\AlgoliaSearch\SearchClient::create(
    config('scout.algolia.id'),
    config('scout.algolia.secret')
);

$securedKey = $client->generateSecuredApiKey(
    'search-only-api-key',
    ['filters' => 'is_public=1']
);
```

Use the secured key in the browser; keep the admin key server-side only.

### Index configuration

Configure replicas, facets, and ranking in your Algolia dashboard or via the API:

```php
$index = $client->initIndex('users');
$index->setSettings([
    'searchableAttributes' => ['name', 'email'],
    'attributesForFaceting' => ['is_active'],
    'customRanking' => ['desc(created_at)'],
]);
```

### Quota and rate limits

Algolia enforces per-plan record and operation limits. Monitor usage in the Algolia dashboard. Set up usage alerts to avoid unexpected overages.

---

## Database driver

The database driver ships with Laravel Scout and requires no external service — useful for smaller datasets or local development.

### When to use

- Datasets under ~50 000 records
- Development/staging environments where a search backend is impractical
- Applications where full-text search precision is less critical

### Configuration

```env
SCOUT_DRIVER=database
```

No additional packages required.

### SQL index recommendations

Add a full-text index on searchable columns for acceptable performance:

```php
// In a migration
$table->fullText(['name', 'email']);
```

For MySQL/MariaDB, full-text indexes use natural language mode by default. Consider switching to boolean mode for more predictable results in large datasets.

### Limitations

| | Database driver | Meilisearch / Typesense / Algolia |
|---|---|---|
| Typo tolerance | No | Yes |
| Relevance ranking | Basic (LIKE) | Advanced |
| Max records | ~50k practical | Millions |
| Performance at scale | Degrades | Consistent |
| Highlighting | No | Yes |

For production with more than a few thousand records, prefer Meilisearch or Typesense.

---

## Monitoring indexes

Verify index health after deploy:

```bash
php artisan scoutify:doctor
```

Check record counts match your database:

```bash
php artisan scout:status   # if available for your driver
```

Set up a cron job to re-import on a schedule if your driver doesn't support real-time sync:

```php
// routes/console.php
Schedule::command('scoutify:sync')->weekly();
```

With `SCOUT_QUEUE=true` and a running queue worker, model saves/deletes sync automatically in near-real-time.
