// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

export default defineConfig({
  site: 'https://matheusmarnt.github.io',
  base: '/scoutify',
  integrations: [
    starlight({
      title: 'Scoutify',
      description: '⌘K global search modal for Laravel — multi-model Livewire UI powered by Scout.',
      logo: {
        src: './src/assets/scoutify_header.png',
        replacesTitle: true,
      },
      favicon: '/favicon.svg',
      customCss: ['./src/styles/custom.css'],
      head: [
        {
          tag: 'meta',
          attrs: { property: 'og:image', content: 'https://matheusmarnt.github.io/scoutify/og-card.png' },
        },
        {
          tag: 'meta',
          attrs: { name: 'twitter:card', content: 'summary_large_image' },
        },
        {
          tag: 'meta',
          attrs: { name: 'twitter:image', content: 'https://matheusmarnt.github.io/scoutify/og-card.png' },
        },
      ],
      editLink: {
        baseUrl: 'https://github.com/matheusmarnt/scoutify/edit/main/docs-site/',
      },
      lastUpdated: true,
      pagination: true,
      social: [
        {
          icon: 'github',
          label: 'GitHub',
          href: 'https://github.com/matheusmarnt/scoutify',
        },
      ],
      sidebar: [
        {
          label: 'Getting Started',
          items: [
            { label: 'Installation',  slug: 'getting-started/installation' },
            { label: 'Quick Start',   slug: 'getting-started/quick-start' },
            { label: 'Configuration', slug: 'getting-started/configuration' },
          ],
        },
        {
          label: 'Usage',
          items: [
            { label: 'Registering Models',  slug: 'usage/registering-models' },
            { label: 'Trigger Components',  slug: 'usage/trigger-components' },
            { label: 'Customizing Builder', slug: 'usage/customizing-builder' },
            { label: 'Programmatic Open',   slug: 'usage/programmatic-open' },
          ],
        },
        {
          label: 'Authorization',
          items: [
            { label: 'Visibility Rules', slug: 'authorization/visibility-rules' },
            { label: 'Policies & Gates', slug: 'authorization/policies-gates' },
            { label: 'Spatie Integration', slug: 'authorization/spatie' },
          ],
        },
        {
          label: 'Reference',
          items: [
            { label: 'Artisan Commands', slug: 'reference/commands' },
            { label: 'Configuration',    slug: 'reference/config' },
            { label: 'Contracts',        slug: 'reference/contracts' },
          ],
        },
        {
          label: 'Production Setup',
          items: [
            { label: 'Meilisearch', slug: 'production/meilisearch' },
            { label: 'Algolia',     slug: 'production/algolia' },
            { label: 'Typesense',   slug: 'production/typesense' },
            { label: 'Database',    slug: 'production/database' },
          ],
        },
      ],
    }),
  ],
});
