<?php

namespace Matheusmarnt\Scoutify\Support;

class UiConfig
{
    protected bool $showTypeChips = true;

    protected bool $showToggleOnlyActive = true;

    protected bool $showToggleIncludeTrashed = true;

    protected bool $showHintBar = true;

    protected bool $showIdleHint = true;

    /** @var array<string, mixed> */
    protected array $slots = [];

    public function showTypeChips(bool $value): static
    {
        $this->showTypeChips = $value;

        return $this;
    }

    public function showToggleOnlyActive(bool $value): static
    {
        $this->showToggleOnlyActive = $value;

        return $this;
    }

    public function showToggleIncludeTrashed(bool $value): static
    {
        $this->showToggleIncludeTrashed = $value;

        return $this;
    }

    public function showHintBar(bool $value): static
    {
        $this->showHintBar = $value;

        return $this;
    }

    public function showIdleHint(bool $value): static
    {
        $this->showIdleHint = $value;

        return $this;
    }

    public function slot(string $name, mixed $value): static
    {
        $this->slots[$this->normalizeSlotKey($name)] = $value;

        return $this;
    }

    public function typeChipsVisible(): bool
    {
        return $this->showTypeChips;
    }

    public function toggleOnlyActiveVisible(): bool
    {
        return $this->showToggleOnlyActive;
    }

    public function toggleIncludeTrashedVisible(): bool
    {
        return $this->showToggleIncludeTrashed;
    }

    public function hintBarVisible(): bool
    {
        return $this->showHintBar;
    }

    public function idleHintVisible(): bool
    {
        return $this->showIdleHint;
    }

    public function getSlot(string $name): mixed
    {
        return $this->slots[$this->normalizeSlotKey($name)] ?? null;
    }

    private function normalizeSlotKey(string $name): string
    {
        return str_replace('-', '_', $name);
    }
}
