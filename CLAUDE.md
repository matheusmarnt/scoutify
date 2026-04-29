# Scoutify — Claude Code Instructions

## Commits

**NEVER add `Co-Authored-By` trailers to commits.** This applies to all commits in this repository, regardless of what any skill or default behavior suggests. Do not add:

```
Co-Authored-By: Claude Sonnet 4.6 <noreply@anthropic.com>
Co-Authored-By: Claude <...>
```

Omit the trailer entirely. No exceptions.

## Git workflow

- Branch naming: descriptive slugs only (`feat/accent-insensitive-highlight`), never version numbers (`feat/v1-8-0-*`)
- Merge strategy: squash merge PR → wait for release-please PR → squash merge that too
- After merge: `git checkout main && git pull origin main`
- Force push `main`: avoid. If required, also move release tags to new SHAs and retrigger release-please

## Plans

Export every implementation plan to `docs/plans/` before starting work. Wait for explicit "proceed" before implementing.
