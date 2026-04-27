@props([
    'icon' => null,
    'clearable' => false,
    'wireModel' => null,
])

@php
    $hasIcon = filled($icon);
@endphp

<div class="relative" x-data="{ value: @entangle($wireModel).live }">
    @if ($hasIcon)
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-zinc-400 dark:text-zinc-500">
            <x-scoutify::gs.icon :name="$icon" class="size-4" />
        </span>
    @endif

    <input
        x-model.debounce.250ms="value"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border border-zinc-200 bg-white py-2 text-sm text-zinc-900 placeholder-zinc-400 outline-none transition focus-visible:border-zinc-300 focus-visible:ring-2 focus-visible:ring-accent/30 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder-zinc-500 [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden '
                . ($hasIcon ? 'pl-9 ' : 'pl-3 ')
                . ($clearable ? 'pr-9' : 'pr-3'),
        ]) }}
    />

    @if ($clearable)
        <button
            type="button"
            x-show="value"
            x-cloak
            @click="value = ''"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 transition hover:text-zinc-600 dark:hover:text-zinc-300"
            aria-label="{{ __('Limpar busca') }}"
        >
            <x-scoutify::gs.icon name="x-mark" class="size-4" />
        </button>
    @endif
</div>
