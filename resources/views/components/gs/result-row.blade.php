@props([
    'id' => null,
    'url',
    'icon',
    'tileClasses' => 'bg-zinc-100 text-zinc-600',
    'titleHtml',
    'subtitleHtml' => null,
    'index' => 0,
    'closeOnClick' => true,
    'rememberQuery' => null,
])

<a
    @if ($id) id="{{ $id }}" @endif
    href="{{ $url }}"
    wire:navigate
    role="option"
    data-search-result
    :aria-selected="{{ $index }} === activeIdx ? 'true' : 'false'"
    @mouseenter="activeIdx = {{ $index }}"
    @click="
        @if ($rememberQuery)
            window.dispatchEvent(new CustomEvent('scoutify:remember', { detail: { term: @js($rememberQuery) } }));
        @endif
        @if ($closeOnClick) $wire.close(); @endif
    "
    class="group relative flex items-center gap-3 rounded-lg px-2.5 py-2.5 transition-colors md:py-2 motion-safe:transition-[transform,colors]"
    x-bind:class="{{ $index }} === activeIdx
        ? 'bg-scoutify-accent/10 ring-1 ring-inset ring-scoutify-accent/20 dark:bg-scoutify-accent/15 outline-none'
        : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/80'"
>
    {{-- Selection indicator strip --}}
    <span
        class="absolute inset-y-2 left-0 w-0.5 rounded-full bg-scoutify-accent opacity-0 transition motion-safe:duration-150"
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

    <x-scoutify::gs.icon name="arrow-right" class="size-3.5 shrink-0 text-zinc-300 opacity-0 transition motion-safe:group-hover:translate-x-0.5 group-hover:opacity-100 dark:text-zinc-600" />
</a>
