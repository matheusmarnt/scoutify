@php
    $recentLimit = config('scoutify.recents.limit', 5);
@endphp

<div
    x-data="{
        recent: $persist([]).as('gs-recent').using(localStorage),
        clear() { this.recent = []; }
    }"
    x-show="recent.length"
    class="px-2 pt-3 pb-1"
    role="region"
    aria-label="{{ __('scoutify::scoutify.recent_searches') }}"
>
    <div class="mb-1 flex items-center gap-1.5 px-2 pb-0.5">
        <x-scoutify::gs.icon name="clock" class="size-3 text-zinc-500 dark:text-zinc-400" />
        <span class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
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

    <template x-for="(term, idx) in recent.slice(0, {{ $recentLimit }})" :key="idx">
        <button
            type="button"
            class="flex w-full items-center gap-3 rounded-lg px-2.5 py-2 text-left transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800/80"
            @click="$wire.set('query', term, true)"
        >
            <div class="flex size-7 shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                <x-scoutify::gs.icon name="arrow-uturn-left" class="size-3.5" />
            </div>
            <span class="truncate text-sm text-zinc-700 dark:text-zinc-300" x-text="term"></span>
        </button>
    </template>
</div>
