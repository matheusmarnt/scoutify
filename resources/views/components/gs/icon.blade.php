@props([
    'name',
    'class' => 'size-4',
])

@php
    $prefix = config('scoutify.icon_prefix', 'heroicon-o-');
    try {
        $registeredPrefixes = collect(app(\BladeUI\Icons\Factory::class)->all())
            ->map(fn (array $set) => preg_quote($set['prefix'] ?? '', '#'))
            ->unique()
            ->filter();
    } catch (\Throwable) {
        $registeredPrefixes = collect();
    }
    $isQualified = $registeredPrefixes->isNotEmpty()
        && (bool) preg_match('#^(' . $registeredPrefixes->implode('|') . ')-#', $name);
    $resolved = $isQualified ? $name : $prefix . ltrim($name, '-');
@endphp

{{-- @svg expects a raw array for its third argument, not a ComponentAttributeBag --}}
@svg($resolved, $class, $attributes->getAttributes())
