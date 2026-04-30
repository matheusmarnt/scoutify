@props([
    'name',
    'class' => 'size-4',
])

@php
    $prefix = config('scoutify.icon_prefix', 'heroicon-o-');
    $isQualified = (bool) preg_match(
        '/^(heroicon|lucide|tabler|solar|mdi|carbon|bi|bx|bxl|bxs|fa|feather|phosphor|ri|gmdi|fluentui|iconpark|ic|icomoon|ion|jam|majesticons|material|octicon|pepicons|polaris|prime|radix|simple|system-uicons|teenyicons|themify|topcoat|typicons|uil|uit|vaadin|websymbol|whh|zondicons)-/',
        $name
    );
    $resolved = $isQualified ? $name : $prefix . ltrim($name, '-');
@endphp

{{-- @svg expects a raw array for its third argument, not a ComponentAttributeBag --}}
@svg($resolved, $class, $attributes->getAttributes())
