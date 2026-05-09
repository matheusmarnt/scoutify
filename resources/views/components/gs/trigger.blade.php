@props(['label' => true])

@php
    $defaultClass = 'group inline-flex h-9 min-w-16 items-center gap-2 rounded-lg border border-zinc-200 bg-white px-3 text-sm text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200';
    $triggerClass = app(\Matheusmarnt\Scoutify\ScoutifyManager::class)->theme()->getTrigger() ?? $defaultClass;
@endphp

<button
    type="button"
    x-data
    @click="$dispatch('scoutify:open')"
    aria-label="{{ __('scoutify::scoutify.open_aria') }}"
    {{ $attributes->merge(['class' => $triggerClass]) }}
>
    <x-scoutify::gs.icon name="magnifying-glass" class="size-4 shrink-0" />
    @if ($label)
        <span class="hidden lg:inline">{{ __('scoutify::scoutify.open') }}</span>
    @endif
    <x-scoutify::gs.kbd
        class="ml-auto hidden lg:inline-flex"
        x-data
        x-text="navigator.platform.toUpperCase().includes('MAC') ? '⌘K' : 'Ctrl K'"
    >Ctrl K</x-scoutify::gs.kbd>
</button>
