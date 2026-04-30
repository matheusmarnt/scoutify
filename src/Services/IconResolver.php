<?php

namespace Matheusmarnt\Scoutify\Services;

use BladeUI\Icons\Factory;

final class IconResolver
{
    public function __construct(private readonly string $prefix = 'heroicon-o-') {}

    public static function make(?string $prefix = null): self
    {
        return new self($prefix ?? config('scoutify.icon_prefix', 'heroicon-o-'));
    }

    public function resolve(string $icon): string
    {
        if (str_starts_with($icon, $this->prefix)) {
            return $icon;
        }

        if (str_contains($icon, '/')) {
            return $icon;
        }

        if ($this->isQualifiedForRegisteredSet($icon)) {
            return $icon;
        }

        return $this->prefix.ltrim($icon, '-');
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    private function isQualifiedForRegisteredSet(string $icon): bool
    {
        try {
            $sets = app(Factory::class)->all();
        } catch (\Throwable) {
            return false;
        }

        foreach ($sets as $set) {
            $setPrefix = $set['prefix'] ?? '';
            if ($setPrefix !== '' && str_starts_with($icon, $setPrefix.'-')) {
                return true;
            }
        }

        return false;
    }
}
