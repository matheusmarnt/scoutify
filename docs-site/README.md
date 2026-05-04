# Scoutify Docs

Astro Starlight site for the `matheusmarnt/scoutify` package.

## Local development

```bash
npm install
npm run dev    # http://localhost:4321/scoutify/
```

## Build

```bash
npm run build
npm run preview
```

## Deploy

Auto-deployed via `.github/workflows/deploy-docs.yml` on push to `main`
when files under `docs-site/**` change. Requires Settings → Pages → Source =
"GitHub Actions" enabled once.

Live at: <https://matheusmarnt.github.io/scoutify/>
