@props([
    'label' => null,
])

@php
    $activeClass = config('scoutify.classes.toggle_active', '');
    $inactiveClass = config('scoutify.classes.toggle_inactive', '');
@endphp

<label class="inline-flex cursor-pointer select-none items-center gap-2">
    <button
        type="button"
        role="switch"
        x-data="{ value: @entangle($attributes->wire('model')->value()).live }"
        :aria-checked="value ? 'true' : 'false'"
        @click="value = ! value"
        :class="value
            ? '{{ $activeClass }}'
            : '{{ $inactiveClass }}'"
        class="relative inline-flex h-4 w-7 shrink-0 items-center rounded-full transition-colors duration-150 focus-visible:outline-hidden focus-visible:ring-2 focus-visible:ring-indigo-500/40"
    >
        <span
            :class="value ? 'translate-x-3.5' : 'translate-x-0.5'"
            class="inline-block size-3 rounded-full bg-white shadow-sm transition-transform duration-150 motion-safe:transition-transform"
        ></span>
    </button>
    @if ($label)
        <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $label }}</span>
    @endif
</label>
