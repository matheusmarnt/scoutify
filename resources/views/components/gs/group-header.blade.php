@props(['icon', 'label', 'total' => null, 'color' => 'zinc'])

@php
    $tileClasses = config("scoutify.colors.$color", config('scoutify.colors.zinc', 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400'));
@endphp

<div class="mb-1 flex items-center gap-1.5 px-2 pb-0.5">
    <x-scoutify::gs.icon-tile :icon="$icon" :tile-classes="$tileClasses" size="sm" />
    <span class="text-[10px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-zinc-400">
        {{ $label }}
    </span>
    @if (! is_null($total))
        <x-scoutify::gs.badge :text="(string) $total" class="ml-auto" />
    @endif
</div>
