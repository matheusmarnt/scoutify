@props(['label' => true])

@php
    $defaultClass = 'lg:hidden inline-flex size-11 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition hover:border-zinc-300 hover:text-zinc-700 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-scoutify-accent/40 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-200';
    $triggerClass = app(\Matheusmarnt\Scoutify\ScoutifyManager::class)->theme()->getTriggerMobile() ?? $defaultClass;
@endphp

<button
    type="button"
    x-data
    @click="$dispatch('scoutify:open')"
    aria-label="{{ __('scoutify::scoutify.open_aria') }}"
    {{ $attributes->merge(['class' => $triggerClass]) }}
>
    <x-scoutify::gs.icon name="magnifying-glass" class="size-5" />
    <span class="sr-only">{{ __('scoutify::scoutify.open') }}</span>
</button>
