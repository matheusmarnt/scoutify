@props([
    'name',
    'class' => 'size-4',
])

@php
    $prefix = config('scoutify.icon_prefix', 'heroicon-o-');
    $isQualified = (bool) preg_match(
        '/^(heroicon|lucide|tabler|solar|mdi|carbon|bi|fa|feather|phosphor|gmdi|fluentui|iconpark|ic|icomoon|majesticons|material|octicon|pepicons|polaris|prime|radix|simple|system-uicons|teenyicons|themify|topcoat|typicons|vaadin|websymbol|whh|zondicons)-/',
        $name
    );
    $resolved = $isQualified ? $name : $prefix . ltrim($name, '-');
@endphp

{{-- @svg expects a raw array for its third argument, not a ComponentAttributeBag --}}
@svg($resolved, $class, $attributes->getAttributes())
