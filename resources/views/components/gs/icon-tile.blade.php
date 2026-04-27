@props([
    'icon',
    'tileClasses' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
    'size' => 'md',
])

@php
    $box = $size === 'sm' ? 'size-7 rounded-md' : 'size-8 rounded-xl';
    $iconCls = $size === 'sm' ? 'size-3.5' : 'size-4';
@endphp

<div {{ $attributes->merge(['class' => "flex shrink-0 items-center justify-center $box $tileClasses"]) }}>
    <x-scoutify::gs.icon :name="$icon" :class="$iconCls" />
</div>
