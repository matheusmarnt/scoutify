---
title: Algolia
description: Production setup for Algolia with Scoutify.
---

import { Aside } from '@astrojs/starlight/components';

Algolia is a robust cloud-hosted search solution that works perfectly with Scoutify.

## Environment Variables

```env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your-app-id
ALGOLIA_SECRET=your-admin-api-key
```

<Aside type="tip">
  For added security, use **Secured API Keys** in the browser to restrict what users can search.
</Aside>

## Secured API Keys

Instead of your admin key, generate a scoped key server-side:

```php
$securedKey = Algolia\AlgoliaSearch\SearchClient::generateSecuredApiKey(
    'search-only-api-key',
    ['filters' => 'is_public=1']
);
```

## Index Configuration

Configure ranking and searchable attributes through the Algolia dashboard or using the SDK:

```php
$index = $client->initIndex('users');
$index->setSettings([
    'searchableAttributes' => ['name', 'email'],
    'customRanking' => ['desc(created_at)'],
]);
```

## Quota and Limits

Algolia enforces record and operation limits based on your plan. Monitor your usage in the Algolia dashboard to avoid unexpected charges.
