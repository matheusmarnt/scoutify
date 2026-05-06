@props([
    'id'           => null,
    'url',
    'icon',
    'groupColor'   => 'zinc',
    'titleHtml',
    'subtitleHtml' => null,
    'index'        => 0,
    'closeOnClick' => true,
    'rememberQuery'=> null,
    'linkTarget'   => 'navigate',
])

@php
    $tileClasses = \Matheusmarnt\Scoutify\Enums\Color::resolveClasses($groupColor);

    // Defense-in-depth: never emit wire:navigate for external URLs even if linkTarget arrives wrong.
    // Livewire navigate removes wire:navigate from DOM after binding the click listener, so DOM
    // inspection shows "no wire:navigate" while the hijack listener is already installed.
    $appHost         = parse_url(url('/'), PHP_URL_HOST);
    $urlHost         = parse_url($url, PHP_URL_HOST);
    $isInternal      = $urlHost === null || $urlHost === $appHost;
    $effectiveTarget = (! $isInternal && $linkTarget === 'navigate') ? '_blank' : $linkTarget;
@endphp

<a
    @if ($id) id="{{ $id }}" @endif
    href="{{ $url }}"
    @if ($effectiveTarget === 'navigate') wire:navigate @endif
    @if ($effectiveTarget === '_blank') target="_blank" rel="noopener noreferrer" @endif
    {{-- _self: plain link, no wire:navigate --}}
    role="option"
    data-search-result
    :aria-selected="{{ $index }} === activeIdx ? 'true' : 'false'"
    @mouseenter="activeIdx = {{ $index }}"
    @click="
        @if ($rememberQuery)
            window.dispatchEvent(new CustomEvent('scoutify:remember', { detail: { term: @js($rememberQuery) } }));
        @endif
        @if ($closeOnClick) setTimeout(() => $wire.close(), 0); @endif
    "
    class="group relative flex items-center gap-3 rounded-lg px-2.5 py-2.5 transition-colors md:py-2 motion-safe:transition-[transform,colors]"
    x-bind:class="{{ $index }} === activeIdx
        ? 'bg-accent/10 ring-1 ring-inset ring-accent/20 dark:bg-accent/15 outline-none'
        : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/80'"
>
    {{-- Selection indicator strip --}}
    <span
        class="absolute inset-y-2 left-0 w-0.5 rounded-full bg-accent opacity-0 transition motion-safe:duration-150"
        x-bind:class="{{ $index }} === activeIdx ? 'opacity-100' : 'opacity-0'"
        aria-hidden="true"
    ></span>

    <x-scoutify::gs.icon-tile :icon="$icon" :tile-classes="$tileClasses" />

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{!! $titleHtml !!}</p>
        @if (! empty($subtitleHtml))
            <p class="truncate text-xs text-zinc-500 dark:text-zinc-400">{!! $subtitleHtml !!}</p>
        @endif
    </div>

    <x-scoutify::gs.icon
        name="heroicon-o-arrow-turn-down-left"
        class="size-3.5 shrink-0 text-zinc-500 transition dark:text-zinc-400"
        x-bind:class="{{ $index }} === activeIdx ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'"
    />
</a>
