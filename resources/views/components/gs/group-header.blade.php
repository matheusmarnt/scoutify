@props(['icon', 'label', 'total' => null, 'color' => 'zinc'])

@php
    $tileClasses = \Matheusmarnt\Scoutify\Enums\Color::resolveClasses($color);
@endphp

<div class="mb-1 flex items-center gap-1.5 px-2 pb-0.5">
    <x-scoutify::gs.icon-tile :icon="$icon" :tile-classes="$tileClasses" size="sm" />
    <span class="text-[11px] font-semibold uppercase tracking-widest text-zinc-600 dark:text-zinc-300">
        {{ $label }}
    </span>
    @if (! is_null($total))
        <x-scoutify::gs.badge :text="(string) $total" class="ml-auto" />
    @endif
</div>
