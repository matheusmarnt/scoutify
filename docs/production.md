# Production Deployment

---

## First deploy

### 1. One-time setup (manual)

**Publish config:**

```bash
php artisan vendor:publish --tag=scoutify-config
```

Generates `config/scoutify.php`. Commit the file — do not re-publish on subsequent deploys.

**Configure the search backend** in `config/scout.php` and set env vars (see driver sections below).

**Add the modal to your root layout** after `$slot`:

```blade
{{-- resources/views/layouts/app.blade.php --}}
{{ $slot }}
<livewire:scoutify::modal />
```

> Nesting `<livewire:scoutify::modal />` inside a child component breaks Alpine scoping.

**Configure queue workers** (required — see Queue workers section below).

---

### 2. First deploy commands (manual or CI/CD)

Run in order:

```bash
# Build the type discovery manifest
php artisan scoutify:rebuild

# Push index settings to the search backend (Meilisearch / Typesense only)
php artisan scout:sync-index-settings

# Populate the index with existing records
php artisan scoutify:import

# Verify everything is healthy
php artisan scoutify:doctor
```

**Docker — first deploy (via exec into running container):**

```bash
docker compose -f compose.production.yaml exec app php artisan scoutify:rebuild
docker compose -f compose.production.yaml exec app php artisan scout:sync-index-settings
docker compose -f compose.production.yaml exec app php artisan scoutify:import
docker compose -f compose.production.yaml exec app php artisan scoutify:doctor
```

**CI/CD — first deploy job:**

```yaml
# Example: GitHub Actions deploy step
- name: Scoutify first deploy
  run: |
    docker compose -f compose.production.yaml exec -T app php artisan scoutify:rebuild
    docker compose -f compose.production.yaml exec -T app php artisan scout:sync-index-settings
    docker compose -f compose.production.yaml exec -T app php artisan scoutify:import
    docker compose -f compose.production.yaml exec -T app php artisan scoutify:doctor
```

---

## Subsequent deploys

### Commands (manual or CI/CD)

Run on every deploy after the first:

```bash
# Rebuild manifest — picks up new/removed Searchable models
php artisan scoutify:rebuild

# Re-apply index settings if config/scout.php changed
php artisan scout:sync-index-settings

# Verify health
php artisan scoutify:doctor
```

> `scoutify:import` is **not** needed on routine deploys. With `SCOUT_QUEUE=true` and a running worker, model saves/deletes stay in sync automatically. Only run `scoutify:import` again if you wiped the index or changed the schema.

**Docker — routine deploy:**

```bash
docker compose -f compose.production.yaml exec app php artisan scoutify:rebuild
docker compose -f compose.production.yaml exec app php artisan scout:sync-index-settings
docker compose -f compose.production.yaml exec app php artisan scoutify:doctor
```

**CI/CD — routine deploy job:**

```yaml
- name: Scoutify deploy
  run: |
    docker compose -f compose.production.yaml exec -T app php artisan scoutify:rebuild
    docker compose -f compose.production.yaml exec -T app php artisan scout:sync-index-settings
    docker compose -f compose.production.yaml exec -T app php artisan scoutify:doctor
  # Exit code != 0 fails the pipeline and blocks the deploy
```

### When index settings change

If you modified `index-settings` (Meilisearch) or `collection-schema` (Typesense) in `config/scout.php`, wipe and re-import — the backend does not migrate existing records automatically:

```bash
php artisan scoutify:sync                     # flush + import all models
php artisan scoutify:sync "App\Models\User"   # or a specific model only
```

---

## Ongoing maintenance

### Automatic sync (requires SCOUT_QUEUE=true + running worker)

Model saves and deletes dispatch Scout jobs automatically. No manual action needed after each record change.

```env
SCOUT_QUEUE=true
```

Without this, every model save blocks the web request with a synchronous HTTP call to the search backend.

### Scheduled re-sync (safety net)

Failed queue jobs can leave records out of sync. Schedule a weekly full re-sync:

```php
// routes/console.php
Schedule::command('scoutify:sync')->weekly();
```

### Scheduled health check

```php
// routes/console.php
Schedule::command('scoutify:doctor')->daily();
```

Wire exit code `0` into your uptime monitor or alerting system.

### Manual recovery

Index drifted or corrupted — full wipe and rebuild:

```bash
php artisan scoutify:sync
```

Specific model only:

```bash
php artisan scoutify:flush "App\Models\User"
php artisan scoutify:import "App\Models\User"
```

---

## Queue workers

### Docker — Supervisor inside the app container (recommended)

The standard production Docker pattern runs all Laravel processes (worker, scheduler, WebSockets) via Supervisor inside the `app` container. Add `laravel-worker.conf` to `docker/production/supervisor/` and mount it:

```yaml
# compose.production.yaml
services:
  app:
    volumes:
      - ./docker/production/supervisor/laravel-worker.conf:/etc/supervisor/conf.d/laravel-worker.conf
```

`docker/production/supervisor/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --queue=emails,default --sleep=3 --tries=3 --max-time=3600 --timeout=3540
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
stopwaitsecs=3600

[program:laravel-schedule]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan schedule:work
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/schedule.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
```

> With `schedule:work` running inside the container, `Schedule::command('scoutify:sync')->weekly()` and `Schedule::command('scoutify:doctor')->daily()` execute automatically — no external cron needed.

### Without Docker — Supervisor

```ini
; /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --queue=emails,default --sleep=3 --tries=3 --max-time=3600 --timeout=3540
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/worker.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Without Docker — Laravel Horizon (recommended for observability)

Horizon provides a real-time dashboard and better process management:

```bash
composer require laravel/horizon
php artisan horizon:install
```

```ini
; /etc/supervisor/conf.d/laravel-horizon.conf
[program:laravel-horizon]
process_name=%(program_name)s
command=php /var/www/html/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/horizon.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
stopwaitsecs=3600
```

---

## Docker

### File naming convention

Docker Compose v2 resolves files in this priority order: `compose.yaml` → `compose.yml` → `docker-compose.yaml` → `docker-compose.yml`. Use `compose.yaml` as the canonical development file and `compose.production.yaml` for production overrides:

```
compose.yaml                  # development (Sail or local)
compose.production.yaml       # production — use with: docker compose -f compose.production.yaml
```

Always reference the production file explicitly:

```bash
docker compose -f compose.production.yaml up -d
docker compose -f compose.production.yaml exec app php artisan scoutify:doctor
```

### Laravel Sail

Sail is the recommended local Docker path for Laravel. Add a search driver:

```bash
php artisan sail:add meilisearch   # or typesense
```

Sail configures the service and networking automatically. Use the **service name** as hostname — not `localhost`:

```env
MEILISEARCH_HOST=http://meilisearch:7700   # ✓ correct inside containers
# MEILISEARCH_HOST=http://localhost:7700   # ✗ resolves to the container itself
```

> `scoutify:doctor` detects this misconfiguration and tells you the fix.

### compose.production.yaml — Meilisearch

Add a `meilisearch` service to your existing production compose file. The worker runs inside `app` via Supervisor — no separate worker container needed:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: docker/production/Dockerfile
    restart: unless-stopped
    env_file:
      - .env
    volumes:
      - ./:/var/www/html
      - ./docker/production/supervisor/laravel-worker.conf:/etc/supervisor/conf.d/laravel-worker.conf
    depends_on:
      - redis
      - meilisearch
    networks:
      - app-network

  nginx:
    image: nginx:stable-alpine
    restart: unless-stopped
    ports:
      - '${APP_PORT}:80'
    volumes:
      - ./:/var/www/html
      - ./docker/production/nginx/conf.d:/etc/nginx/conf.d
    depends_on:
      - app
    networks:
      - app-network

  redis:
    image: redis:alpine
    restart: unless-stopped
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis-data:/data
    networks:
      - app-network

  meilisearch:
    image: getmeili/meilisearch:latest
    restart: unless-stopped
    environment:
      MEILI_MASTER_KEY: ${MEILI_MASTER_KEY}
      MEILI_ENV: production
    volumes:
      - meilisearch-data:/meili_data
    networks:
      - app-network
    # Do NOT expose port 7700 publicly — access only within app-network

networks:
  app-network:
    driver: bridge

volumes:
  redis-data:
  meilisearch-data:
```

`.env` additions:

```env
SCOUT_DRIVER=meilisearch
SCOUT_QUEUE=true
MEILISEARCH_HOST=http://meilisearch:7700
MEILISEARCH_KEY=your-search-only-api-key
MEILI_MASTER_KEY=your-strong-master-key
```

### compose.production.yaml — Typesense

```yaml
services:
  app:
    # ... same as above ...
    depends_on:
      - redis
      - typesense

  typesense:
    image: typesense/typesense:latest
    restart: unless-stopped
    environment:
      TYPESENSE_DATA_DIR: /data
      TYPESENSE_API_KEY: ${TYPESENSE_API_KEY}
    volumes:
      - typesense-data:/data
    networks:
      - app-network
    # Do NOT expose port 8108 publicly — access only within app-network

volumes:
  typesense-data:
```

`.env` additions:

```env
SCOUT_DRIVER=typesense
SCOUT_QUEUE=true
TYPESENSE_HOST=typesense
TYPESENSE_PORT=8108
TYPESENSE_PROTOCOL=http
TYPESENSE_API_KEY=your-api-key
```

> Always run Meilisearch and Typesense behind a TLS-terminating reverse proxy (nginx, Caddy) in production. Never expose ports 7700 or 8108 to the public internet.

---

## Meilisearch

### Environment variables

```env
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=https://your-meilisearch-host:7700
MEILISEARCH_KEY=your-search-only-api-key
```

Use a **search-only API key** (never the master key) for `MEILISEARCH_KEY`. Generate one via the Meilisearch `/keys` endpoint or dashboard.

> **Word-boundary limitation:** Meilisearch uses prefix search at word boundaries. A query `"ano"` will not match `"Mariano"` — only prefixes like `"Mar"` match. Override `globalSearchBuilder()` on your model for custom matching, or switch to the database driver for LIKE-based substring search.

### Index settings

Filterable and sortable attributes must be declared before indexing. Configure in `config/scout.php`:

```php
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

Apply after any change:

```bash
php artisan scout:sync-index-settings
```

### HTTPS + API key rotation

1. Run Meilisearch behind a TLS-terminating reverse proxy (nginx, Caddy).
2. Generate a search-only key via `/keys` with `["search"]` actions only — never expose the master key.
3. Rotate keys via the Meilisearch API and update `MEILISEARCH_KEY` in `.env` without downtime.

### Self-hosted (standalone Docker)

```bash
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

Apply after any schema change, then wipe and re-import:

```bash
php artisan scout:sync-index-settings
php artisan scoutify:sync
```

### Self-hosted vs Typesense Cloud

| | Self-hosted | Typesense Cloud |
|---|---|---|
| Cost | Infra only | Usage-based |
| TLS | Configure via reverse proxy | Included |
| Scaling | Manual | Automatic |
| Backups | Manual | Included |

Self-hosted: run behind nginx/Caddy for TLS. Use `--enable-cors` only if browser-direct access is needed.

---

## Algolia

### Environment variables

```env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your-app-id
ALGOLIA_SECRET=your-admin-api-key
```

### Secured API keys (recommended for production)

Never expose the admin API key on the client side. Generate a scoped API key server-side:

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

Configure replicas, facets, and ranking via the Algolia dashboard or API:

```php
$index = $client->initIndex('users');
$index->setSettings([
    'searchableAttributes' => ['name', 'email'],
    'attributesForFaceting' => ['is_active'],
    'customRanking' => ['desc(created_at)'],
]);
```

### Quota and rate limits

Algolia enforces per-plan record and operation limits. Monitor usage in the Algolia dashboard and set up usage alerts to avoid unexpected overages.

---

## Database driver

Ships with Laravel Scout — no external service required. Useful for smaller datasets or environments where running a search backend is impractical.

### When to use

- Datasets under ~50 000 records
- Staging environments where a full search backend is not worth the overhead
- Applications where typo tolerance and relevance ranking are not required

### Configuration

```env
SCOUT_DRIVER=database
```

No additional packages or services required.

### SQL index recommendations

Add a full-text index on searchable columns:

```php
// In a migration
$table->fullText(['name', 'email']);
```

MySQL/MariaDB full-text indexes use natural language mode by default. Switch to boolean mode for more predictable results on larger datasets.

### Limitations

| | Database driver | Meilisearch / Typesense / Algolia |
|---|---|---|
| Typo tolerance | No | Yes |
| Relevance ranking | Basic (LIKE) | Advanced |
| Max records | ~50k practical | Millions |
| Performance at scale | Degrades | Consistent |
| Highlighting | No | Yes |

For production with more than a few thousand records, prefer Meilisearch or Typesense.
