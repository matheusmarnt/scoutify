@php
    $recentLimit = config('scoutify.recents.limit', 5);
@endphp

<div
    x-data="{
        recent: $persist([]).as('scoutify-recent').using(localStorage),
        clear() { this.recent = []; }
    }"
    x-show="recent.length"
    class="px-2 pt-3 pb-1"
    role="region"
    aria-label="{{ __('scoutify::scoutify.recent_searches') }}"
>
    <div class="mb-1 flex items-center gap-1.5 px-2 pb-0.5">
        <x-scoutify::gs.icon name="clock" class="size-3 text-zinc-500 dark:text-zinc-300" />
        <span class="text-[10px] font-semibold uppercase tracking-widest text-zinc-600 dark:text-zinc-200">
            {{ __('scoutify::scoutify.recent') }}
        </span>
        <button
            type="button"
            @click="clear()"
            class="ml-auto text-[10px] text-zinc-400 transition hover:text-zinc-600 dark:hover:text-zinc-200"
        >
            {{ __('scoutify::scoutify.clear') }}
        </button>
    </div>

    <div class="grid grid-cols-4 gap-1">
        <template x-for="(term, idx) in recent.slice(0, {{ $recentLimit }})" :key="idx">
            <button
                type="button"
                data-search-result
                @mouseenter="activeIdx = allResults.indexOf($el)"
                x-bind:class="allResults.indexOf($el) === activeIdx
                    ? 'bg-accent/10 ring-1 ring-inset ring-accent/20 dark:bg-accent/15 outline-none'
                    : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/80'"
                class="relative flex items-center gap-2 overflow-hidden rounded-lg px-2 py-1.5 text-left transition-colors"
                @click="$wire.set('query', term, true)"
            >
                {{-- Selection indicator strip --}}
                <span
                    class="absolute inset-y-1.5 left-0 w-0.5 rounded-full bg-accent opacity-0 transition motion-safe:duration-150"
                    :class="allResults.indexOf($el.closest('[data-search-result]')) === activeIdx ? 'opacity-100' : 'opacity-0'"
                    aria-hidden="true"
                ></span>
                <div class="flex size-5 shrink-0 items-center justify-center rounded bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <x-scoutify::gs.icon name="arrow-uturn-left" class="size-3" />
                </div>
                <span class="truncate text-xs text-zinc-700 dark:text-zinc-300" x-text="term"></span>
            </button>
        </template>
    </div>
</div>
