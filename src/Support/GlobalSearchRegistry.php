<?php

namespace Matheusmarnt\Scoutify\Support;

final class GlobalSearchRegistry
{
    /** @var array<class-string, array{key: string, label: string, icon: string, color: string}> */
    private array $entries = [];

    public function register(string $modelClass, array $meta): void
    {
        $this->entries[$modelClass] = $meta;
    }

    public function all(): array
    {
        return $this->entries;
    }

    public function get(string $modelClass): ?array
    {
        return $this->entries[$modelClass] ?? null;
    }

    public function has(string $modelClass): bool
    {
        return isset($this->entries[$modelClass]);
    }
}
