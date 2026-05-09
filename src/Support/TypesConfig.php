<?php

namespace Matheusmarnt\Scoutify\Support;

class TypesConfig
{
    protected string $iconPrefix = '';

    /** @var array<class-string, array<string, mixed>> */
    protected array $types = [];

    public function register(string $class, string $label = '', string $icon = '', string $color = 'zinc'): static
    {
        $this->types[$class] = array_filter([
            'key' => $class,
            'label' => $label,
            'icon' => $icon,
            'color' => $color,
        ], fn ($v) => $v !== '');

        return $this;
    }

    public function iconPrefix(string $prefix): static
    {
        $this->iconPrefix = $prefix;

        return $this;
    }

    public function getIconPrefix(): string
    {
        return $this->iconPrefix;
    }

    /**
     * @return array<class-string, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->types;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $class): ?array
    {
        return $this->types[$class] ?? null;
    }
}
