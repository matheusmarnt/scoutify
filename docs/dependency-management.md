# Dependency Management

## Dependabot

Scoutify's dependencies are kept current via GitHub Dependabot (`.github/dependabot.yml`).

### Ecosystems monitored

| Ecosystem | Directory | Cadence | Grouping |
|-----------|-----------|---------|---------|
| `composer` | `/` | Weekly | Dev tooling (Pest, PHPStan, Larastan, Pint, Testbench) batched; Laravel framework/Scout patches batched |
| `docker` | `/stubs` | Weekly | Individual PR per image bump |
| `github-actions` | `/` | Weekly | Individual PRs |

### Composer groups

Dependabot opens **one PR** for all minor/patch bumps within each group:

- **dev-tooling** — `pestphp/*`, `phpstan/*`, `larastan/*`, `laravel/pint`, `orchestra/testbench`
- **laravel-framework** — `laravel/framework`, `laravel/scout` (patch only)

Major bumps and driver clients (`algolia`, `meilisearch`, `typesense`) always land as individual PRs.

### Docker image bumps (stubs)

Dependabot watches `image:` tags in `stubs/compose.scoutify.yaml` and `stubs/compose.scoutify.typesense.yaml`.

When a docker-image PR arrives:

1. Pull the PR branch locally.
2. Run `php artisan scoutify:install --driver=<driver> --patch-compose=skip` against a fresh Laravel app.
3. Confirm `php artisan scoutify:doctor` reports the service healthy.
4. Merge — release-please will cut a patch release automatically.

---

## Local Dependency Updates

Check drift against current constraints:

```bash
composer outdated --direct
```

Apply updates within existing constraints:

```bash
composer update --with-all-dependencies
vendor/bin/pest --no-coverage
php -d memory_limit=512M vendor/bin/phpstan analyse
vendor/bin/pint
```

Raise a constraint floor when a new minor version adds a feature you need:

```bash
composer require "meilisearch/meilisearch-php:^1.17"
# run full suite, commit on a chore/* branch
```
