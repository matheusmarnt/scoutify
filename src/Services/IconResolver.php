<?php

namespace Matheusmarnt\Scoutify\Services;

final class IconResolver
{
    public function __construct(private readonly string $prefix = 'heroicon-o-') {}

    public static function make(?string $prefix = null): self
    {
        return new self($prefix ?? config('scoutify.icon_prefix', 'heroicon-o-'));
    }

    /**
     * Resolve a short icon name to fully-qualified blade-icon string.
     * If already prefixed, returns as-is.
     */
    public function resolve(string $icon): string
    {
        if (str_starts_with($icon, $this->prefix)) {
            return $icon;
        }

        if (str_contains($icon, '/')) {
            return $icon;
        }

        return $this->prefix.ltrim($icon, '-');
    }

    public function prefix(): string
    {
        return $this->prefix;
    }
}
