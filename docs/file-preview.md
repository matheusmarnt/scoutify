# File Preview & Download

Any model can expose an inline file preview pane inside the search modal by implementing `HasGlobalSearchPreview`.

## Basic setup

```php
use Matheusmarnt\Scoutify\Contracts\HasGlobalSearchPreview;
use Matheusmarnt\Scoutify\Support\PreviewDto;

class Document extends Model implements GloballySearchable, HasGlobalSearchPreview
{
    use Searchable;

    public function globalSearchPreview(): ?PreviewDto
    {
        return PreviewDto::fromDisk(
            disk: 'documents',
            path: $this->file_path,
        );
    }
}
```

Return `null` to suppress the preview pane for a specific record.

## `PreviewDto` factory methods

### `PreviewDto::fromDisk()`

Use when the file lives on a Laravel filesystem disk:

```php
PreviewDto::fromDisk(
    disk: 'documents',           // disk key from config/filesystems.php
    path: $this->file_path,      // path relative to the disk root
    mime: 'application/pdf',     // optional — auto-detected from disk if omitted
    filename: $this->name,       // optional — defaults to basename($path)
    sizeBytes: $this->size,      // optional
    ttl: 3600,                   // optional — signed URL TTL in seconds (default 3600)
)
```

**URL resolution:** if the disk supports temporary URLs (e.g. S3), Scoutify uses them directly. Otherwise it streams through the auto-registered `scoutify.preview.stream` signed route — no manual route publishing needed.

### `PreviewDto::fromUrl()`

Use when the file is already publicly accessible:

```php
PreviewDto::fromUrl(
    url: 'https://cdn.example.com/reports/q1.pdf',
    mime: 'application/pdf',  // optional
    filename: 'Q1 Report',    // optional — defaults to basename of URL path
)
```

## Viewer selection

| MIME | Viewer |
|---|---|
| `application/pdf` | Native PDF embed |
| `image/*` | Inline image |
| `video/*` | HTML5 video player |
| Anything else | Fallback — external link and download button |

## Download

Add a listener to your root layout to handle the `scoutify:download` browser event:

```js
window.addEventListener('scoutify:download', (e) => {
    const a = document.createElement('a');
    a.href = e.detail.url;
    a.download = e.detail.filename ?? '';
    document.body.appendChild(a);
    a.click();
    a.remove();
});
```

## Authorization

Preview and download reuse `GlobalSearchAuthorizer`. A user who cannot see a record in search results cannot stream or download its file.

## Keyboard

**Results list (preview closed):**

| Key | Action |
|---|---|
| `Tab` | Moves focus from the search input to the **Preview** button on the active row. A second `Tab` moves to the **Download** button. A third `Tab` returns focus to the search input. |
| `Shift+Tab` | Reverses the cycle: input ← Download ← Preview. |
| `Enter` | When focus is on a Preview or Download button, activates it without navigating to the record's route. |
| `↑` / `↓` | Navigate rows. If an action button is focused, focus returns to the input before the row changes. |

**Preview pane (preview open):**

| Key | Action |
|---|---|
| `Esc` | Closes the preview pane and returns to the results list. A second `Esc` dismisses the modal. |
| `Enter` | When focus is on the Back button (auto-focused on open), closes the preview. |
| `Tab` | Cycles through the Back button and the download link in the preview header. |
| `↑` / `↓` / `PageDown` / `PageUp` | Not intercepted by Scoutify — the browser forwards them to the embedded PDF/video viewer. |

## Custom viewer

Set the `view` parameter to override the Blade view used for a specific record:

```php
PreviewDto::fromDisk(
    disk: 'documents',
    path: $this->file_path,
    view: 'my-package::preview.custom', // receives $dto and $url
)
```
