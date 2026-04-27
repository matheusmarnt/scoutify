@props(['size' => 'sm'])

@php
    $sizes = [
        'xs' => 'min-w-[16px] px-1 py-0.5 text-[10px]',
        'sm' => 'min-w-[20px] px-1.5 py-0.5 text-[10px]',
        'md' => 'min-w-[24px] px-2 py-1 text-xs',
    ];
    $cls = $sizes[$size] ?? $sizes['sm'];
@endphp

<kbd
    role="img"
    aria-label="{{ trim(strip_tags((string) $slot)) }}"
    {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded border border-zinc-200 bg-zinc-50 font-medium text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 $cls"]) }}
>
    {{ $slot }}
</kbd>
