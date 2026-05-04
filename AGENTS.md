# AGENTS.md — Scoutify

Instructions for AI agents (Claude, Copilot, Codex, Gemini) working in this repository.

---

## Package Overview

`matheusmarnt/scoutify` — Laravel Scout UI layer. Provides a global search modal (Livewire + Alpine.js) with grouped results, per-model authorization, icon resolution, HTML sanitization, and CLI install tooling.

**Current version:** 1.12.0 | **PHP:** ^8.1 | **Laravel:** ^10|^11|^12

---

## Repository Structure

```
src/
  Authorization/        # VisibilityRule builder, GlobalSearchAuthorizer, VisibilityMode enum
  Concerns/             # Searchable trait (extends Scout)
  Console/              # Artisan commands (install, import, sync, rebuild, flush, doctor)
  Contracts/            # GloballySearchable, HasGlobalSearchVisibility
  Enums/                # Color enum
  Livewire/             # Modal.php (main search modal component)
  Services/             # SearchAggregator, IconResolver, ModelDiscoverer, ScoutConfigurator
  Support/              # GlobalSearchRegistry, ResultDto, Highlighter, Sanitizer, TypeManifest
config/                 # scoutify.php
resources/              # views, lang (en/es/pt_BR), css
tests/
  Feature/
    Authorization/      # VisibilityRuleTest, GlobalSearchAuthorizerTest
    Services/           # SearchAggregatorAuthorizationTest + others
  Fixtures/Models/      # Test model fixtures
docs/plans/             # Feature planning documents
```

---

## Running Tests

```bash
# Full suite
vendor/bin/pest

# Specific area
vendor/bin/pest tests/Feature/Authorization/
vendor/bin/pest tests/Feature/Services/

# Static analysis
vendor/bin/phpstan analyse
```

Always run full suite before finishing. Target: all tests green + phpstan clean.

---

## Coding Conventions

- **PHP 8.1+**: use enums, named arguments, readonly where appropriate.
- **No comments** unless WHY is non-obvious.
- **No backwards-compat hacks** — change code directly.
- **Optional dependencies** (Spatie, blade-icons): detect via `method_exists` / try-catch. Never import directly.
- **No error handling for impossible scenarios** — trust Laravel/Scout guarantees.
- Pint for code style (`vendor/bin/pint`).

---

## Authorization System

`SearchAggregator` delegates to `app(GlobalSearchAuthorizer::class)->authorize($record, $user)`.

Models opt-in via `HasGlobalSearchVisibility` contract:

```php
public function globalSearchVisibility(): VisibilityRule
{
    return VisibilityRule::make()
        ->visibleToGuests()
        ->orWhenAuthenticated()->policy('view');
}
```

Default (no contract): `secure` — guests denied, authed users pass Gate::check('view') or allow if no policy.

Config: `config/scoutify.php` → `authorization.default` (`secure|permissive|gate-only`).

---

## Starting Implementation

**Before writing any code**, create the branch and worktree. No exceptions — not even for "quick" fixes.

```bash
git fetch origin
git worktree add .worktrees/<branch-name> -b <branch-name> origin/main
```

Branch name MUST follow [Branch Naming](#branch-naming) conventions below. Derive the name from the plan file or task description before touching any file.

---

## Worktree Workflow

Features developed in `.worktrees/<branch-name>/` (git worktree).

**After merge (squash):**
```bash
git push origin --delete <branch-name>
git worktree remove .worktrees/<branch-name>
```

Squash merge = original SHA never lands on main. `git log main..<branch>` will show "unmerged" even after PR closes — this is expected.

---

## Branch Naming

```
feat/<kebab-feature-name>
fix/<kebab-description>
docs/<kebab-description>
chore/<kebab-description>
```

---

## Release

Automated via release-please (GitHub Actions). Do not bump version manually. Releases trigger on merge of release-please PR to `main`.

---

## What NOT to Do

- Do not mock `GlobalSearchAuthorizer` by default in integration tests — use real authorizer with fixture models.
- Do not import `Spatie\Permission\*` classes directly — detect via reflection.
- Do not add `globalSearchVisibility()` to `GloballySearchable` contract — it's a separate opt-in contract.
- Do not force-push to `main`.

---

## Docs Site

Astro Starlight project under `docs-site/`. Live at <https://matheusmarnt.github.io/scoutify/>.

**Working in docs**:
```bash
cd docs-site
npm install
npm run dev      # http://localhost:4321/scoutify/
npm run build    # writes dist/
```

**Conventions**:
- Internal links MUST be prefixed with `/scoutify/...` (Astro `base`).
- Brand tokens defined in `src/styles/custom.css` — do not hand-edit accent values; use the `--sc-*` palette.
- Hand-written MD/MDX. Never auto-generate from PHP source.
- `/docs/*.md` and `/installation.md` (root) are intentionally kept as parallel raw markdown — do not delete; do not sync.

**Adding a page**: create the `.md` file under the right `src/content/docs/<group>/` folder, then add it to the `sidebar` array in `astro.config.mjs`.

**Deploy**: handled by `.github/workflows/deploy-docs.yml` on push to `main` when `docs-site/**` changes.
