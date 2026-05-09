<?php

namespace Matheusmarnt\Scoutify\Support;

class ThemeConfig
{
    protected ?string $accent = null;

    protected ?string $input = null;

    protected ?string $trigger = null;

    protected ?string $triggerMobile = null;

    protected ?string $toggleActive = null;

    protected ?string $toggleInactive = null;

    protected ?string $dialogScrim = null;

    protected ?string $dialogPanel = null;

    /** @var array<string, array{light: string, dark: string}> */
    protected array $customColors = [];

    public function accent(string $value): static
    {
        $this->accent = $value;

        return $this;
    }

    public function input(string $value): static
    {
        $this->input = $value;

        return $this;
    }

    public function trigger(string $value): static
    {
        $this->trigger = $value;

        return $this;
    }

    public function triggerMobile(string $value): static
    {
        $this->triggerMobile = $value;

        return $this;
    }

    public function toggleActive(string $value): static
    {
        $this->toggleActive = $value;

        return $this;
    }

    public function toggleInactive(string $value): static
    {
        $this->toggleInactive = $value;

        return $this;
    }

    public function dialogScrim(string $value): static
    {
        $this->dialogScrim = $value;

        return $this;
    }

    public function dialogPanel(string $value): static
    {
        $this->dialogPanel = $value;

        return $this;
    }

    public function color(string $name, string $light, string $dark): static
    {
        $this->customColors[$name] = ['light' => $light, 'dark' => $dark];

        return $this;
    }

    public function getAccent(): ?string
    {
        return $this->accent;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function getTrigger(): ?string
    {
        return $this->trigger;
    }

    public function getTriggerMobile(): ?string
    {
        return $this->triggerMobile;
    }

    public function getToggleActive(): ?string
    {
        return $this->toggleActive;
    }

    public function getToggleInactive(): ?string
    {
        return $this->toggleInactive;
    }

    public function getDialogScrim(): ?string
    {
        return $this->dialogScrim;
    }

    public function getDialogPanel(): ?string
    {
        return $this->dialogPanel;
    }

    public function resolveColorClasses(string $name): ?string
    {
        if (! isset($this->customColors[$name])) {
            return null;
        }

        return $this->customColors[$name]['light'].' '.$this->customColors[$name]['dark'];
    }

    /** @return array<string, array{light: string, dark: string}> */
    public function customColors(): array
    {
        return $this->customColors;
    }
}
