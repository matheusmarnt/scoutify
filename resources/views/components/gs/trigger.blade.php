@props(['label' => true])

@php
    $triggerClass = config('scoutify.classes.trigger', '');
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
    <x-scoutify::gs.kbd class="ml-auto hidden lg:inline-flex">⌘K</x-scoutify::gs.kbd>
</button>
