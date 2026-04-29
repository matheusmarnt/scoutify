@props(['label' => true])

@php($triggerClass = config('scoutify.classes.trigger_mobile', ''))

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
