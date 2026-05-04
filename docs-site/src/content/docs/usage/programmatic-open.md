---
title: Programmatic Open
description: Learn how to open the search modal from your own code.
---

You can trigger the search modal from anywhere in your application using JavaScript or PHP.

## Alpine.js

If you're using Alpine.js, you can dispatch the `scoutify:open` event:

```html
<button x-on:click="$dispatch('scoutify:open')">
    Search Site
</button>
```

## Plain JavaScript

From vanilla JavaScript, use the `window.dispatchEvent` method:

```javascript
window.dispatchEvent(new CustomEvent('scoutify:open'));
```

## Livewire (PHP)

From a Livewire component, you can dispatch the event to the modal component:

```php
public function openSearch()
{
    $this->dispatch('scoutify:open')->to('scoutify::modal');
}
```

Or a global dispatch:

```php
public function openSearch()
{
    $this->dispatch('scoutify:open');
}
```
