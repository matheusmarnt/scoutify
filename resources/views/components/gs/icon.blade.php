@props([
    'name',
    'class' => 'size-4',
])

@php
    $prefix = config('scoutify.icon_prefix', 'heroicon-o-');
    $registeredPrefixes = collect(config('blade-icons.sets', []))
        ->pluck('prefix')
        ->filter()
        ->unique()
        ->map(fn ($p) => preg_quote($p, '#'));
    $isQualified = $registeredPrefixes->isNotEmpty()
        && (bool) preg_match('#^(' . $registeredPrefixes->implode('|') . ')-#', $name);
    $resolved = $isQualified ? $name : $prefix . ltrim($name, '-');
@endphp

{{-- @svg expects a raw array for its third argument, not a ComponentAttributeBag --}}
@svg($resolved, $class, $attributes->getAttributes())
