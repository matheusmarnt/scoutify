---
title: Contracts
description: Technical reference for Scoutify interface contracts.
---

Scoutify uses two main interfaces to integrate with your Eloquent models.

## `GloballySearchable`

This contract is required for any model you want to appear in the search results.

```php
namespace Matheusmarnt\Scoutify\Contracts;

interface GloballySearchable
{
    public function globalSearchTitle(): string;
    public function globalSearchSubtitle(): ?string;
    public function globalSearchUrl(): string;
}
```

### Methods
- `globalSearchTitle()`: Returns the string displayed as the main title.
- `globalSearchSubtitle()`: Returns a secondary string displayed below the title.
- `globalSearchUrl()`: Returns the absolute URL the user is redirected to upon clicking.

## `HasGlobalSearchVisibility`

This optional contract allows you to define custom visibility rules for a model.

```php
namespace Matheusmarnt\Scoutify\Contracts;

use Matheusmarnt\Scoutify\Authorization\VisibilityRule;

interface HasGlobalSearchVisibility
{
    public function globalSearchVisibility(): VisibilityRule;
}
```

### Methods
- `globalSearchVisibility()`: Returns a `VisibilityRule` instance using the fluent builder.

## `HasGlobalSearchPreview`

This optional contract enables an inline file preview pane inside the search modal for a model's results.

```php
namespace Matheusmarnt\Scoutify\Contracts;

use Matheusmarnt\Scoutify\Support\PreviewDto;

interface HasGlobalSearchPreview
{
    public function globalSearchPreview(): ?PreviewDto;
}
```

### Methods
- `globalSearchPreview()`: Returns a `PreviewDto` describing the file to preview, or `null` to show no preview pane.

### `PreviewDto`

`PreviewDto` is an immutable value object created via two factory methods:

```php
// File on a Laravel filesystem disk
PreviewDto::fromDisk(
    disk: 'documents',
    path: 'reports/q1.pdf',
    mime: 'application/pdf',    // optional — auto-detected from disk if omitted
    filename: 'Q1 Report.pdf',  // optional — defaults to basename($path)
    sizeBytes: 204800,          // optional
    view: null,                 // optional — custom Blade view name
    ttl: 3600,                  // optional — signed URL TTL in seconds
);

// Publicly-accessible external URL
PreviewDto::fromUrl(
    url: 'https://cdn.example.com/file.pdf',
    mime: null,     // optional
    filename: null, // optional — defaults to basename of the URL path
);
```

**Viewer selection** (automatic, based on resolved MIME type):

| MIME prefix | Viewer |
|---|---|
| `application/pdf` | Native PDF embed (`<embed>`) |
| `image/*` | `<img>` tag |
| `video/*` | `<video>` player |
| Anything else | Fallback — external link / download button |

**Authorization:** The preview stream route reuses `GlobalSearchAuthorizer`. A user who cannot see the record in search results also cannot stream or download the file.

**Download event:** When the user clicks the download button, Scoutify dispatches a `scoutify:download` browser event with `{ url, filename }` detail. Handle it in your layout:

```js
window.addEventListener('scoutify:download', (e) => {
    const a = document.createElement('a');
    a.href = e.detail.url;
    a.download = e.detail.filename ?? '';
    a.click();
});
```

## Trait Helpers

The `Matheusmarnt\Scoutify\Concerns\Searchable` trait provides default implementations for all methods in `GloballySearchable`, including auto-discovery of titles and subtitles.
