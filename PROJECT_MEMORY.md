# Scoutify — Project Memory

**Package:** `matheusmarnt/scoutify`
**Current version:** `1.12.0`
**Main branch:** `main` (squash-merge workflow via GitHub PRs)

---

## What This Package Does

Laravel Scout UI layer: global search modal (Livewire + Alpine.js) with grouped results, icon resolution, per-model authorization, and CLI tools for zero-config setup.

---

## Architecture

| Layer | Key files |
|-------|-----------|
| Modal UI | `src/Livewire/Modal.php`, `resources/views/livewire/modal.blade.php` |
| Search pipeline | `src/Services/SearchAggregator.php` |
| Authorization | `src/Authorization/` (GlobalSearchAuthorizer, VisibilityRule, VisibilityMode) |
| Icon resolution | `src/Services/IconResolver.php` |
| Model discovery | `src/Services/ModelDiscoverer.php` |
| Contracts | `src/Contracts/GloballySearchable.php`, `src/Contracts/HasGlobalSearchVisibility.php` |
| Support | `src/Support/` (GlobalSearchRegistry, GlobalSearchGroup, ResultDto, Highlighter, Sanitizer) |
| CLI | `src/Console/` (install, import, sync, rebuild, flush, doctor, searchable) |

---

## Key Design Decisions

### Authorization (v1.12.0)
- `SearchAggregator` delegates to `GlobalSearchAuthorizer::authorize($record, $user)` — centralizado.
- Default: `secure` — guests negados, autenticados passam por `Gate::check('view')` se policy existir, senão allow.
- Models implementam `HasGlobalSearchVisibility` (optional contract) retornando `VisibilityRule` builder fluente.
- Spatie integration: detecção via `method_exists`, sem import direto — mantém dependência opcional.
- Config block `scoutify.authorization.default` aceita `secure | permissive | gate-only`.

### Icon Resolution
- `IconResolver` detecta prefixos de packs blade-icons de terceiros via `Factory::all()` com try/catch.
- GlobalSearchGroup icons não são mangulados — prefixo preservado.

### HTML Sanitization (v1.11.0)
- Subtitle auto-discovery sanitiza HTML → plain text via `Sanitizer`.
- Dev pode sobrescrever `Sanitizer` via container.

### Mobile trigger (v1.10.0)
- Componente `<x-scoutify::mobile-trigger />` separado — não acoplado ao modal.

---

## Implemented Features (release history relevante)

| Versão | Feature |
|--------|---------|
| 1.12.0 | Per-model global search visibility (`VisibilityRule`, `GlobalSearchAuthorizer`) |
| 1.11.x | Icon prefix detection para third-party blade-icon packs |
| 1.11.0 | HTML sanitization em subtitle auto-discovery |
| 1.10.0 | Mobile-only trigger component |

---

## Workflow Notes

- **Merge strategy:** squash merge — branches originais têm SHAs diferentes de `main` após merge.
- **Worktrees:** usados para features longas em `.worktrees/<branch-name>/`.
- **Limpeza pós-merge:** deletar branch remota (`git push origin --delete <branch>`) e remover worktree (`git worktree remove .worktrees/<name>`).
- **Release:** release-please via GitHub Actions gera PR de release automático.

### Limpeza realizada em 2026-05-04
- Deletado `origin/feat/global-search-visibility` (squash-merged em `136e4c3` → PR #87).
- Removido worktree `.worktrees/global-search-visibility/`.

---

## Optional Dependencies

| Package | Usado em |
|---------|----------|
| `spatie/laravel-permission ^6.0` | `VisibilityRule::permission()` / `role()` |
| `blade-icons/*` | Icon resolution (qualquer pack) |

---

## Testing

```bash
vendor/bin/pest                          # suite completa
vendor/bin/pest tests/Feature/Authorization/   # auth unit tests
vendor/bin/phpstan analyse               # static analysis
```

Test fixtures de models em `tests/Fixtures/Models/`.

---

## Docs Site

- **URL**: https://matheusmarnt.github.io/scoutify/
- **Stack**: Astro 6.x + Starlight 0.38.x + Node 22.
- **Path**: `docs-site/` (parallel to `src/`).
- **Deploy**: `.github/workflows/deploy-docs.yml` — path-filtered on `docs-site/**`. Triggers only when docs change. PR-able via `workflow_dispatch`.
- **Activation**: GitHub Settings → Pages → Source = "GitHub Actions" (one-time manual step).
- **Brand tokens**: `--sc-cyan`, `--sc-indigo`, `--sc-violet*` extracted from `art/scoutify.png`. Accent = `#7c3aed` (violet-deep).
- **Content source of truth**: hand-written MD/MDX in `docs-site/src/content/docs/`. `/docs/*.md` and `/installation.md` (root) are preserved as parallel raw markdown.
