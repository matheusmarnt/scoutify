<?php

namespace Matheusmarnt\Scoutify;

use Matheusmarnt\Scoutify\Support\ThemeConfig;
use Matheusmarnt\Scoutify\Support\TypesConfig;
use Matheusmarnt\Scoutify\Support\UiConfig;

class ScoutifyManager
{
    protected TypesConfig $types;

    protected ThemeConfig $theme;

    /** @var \Closure(UiConfig): void|null */
    protected ?\Closure $uiResolver = null;

    public function __construct()
    {
        $this->types = new TypesConfig;
        $this->theme = new ThemeConfig;
    }

    public function types(): TypesConfig
    {
        return $this->types;
    }

    public function theme(): ThemeConfig
    {
        return $this->theme;
    }

    public function configureUi(\Closure $resolver): void
    {
        $this->uiResolver = $resolver;
    }

    public function resolveUi(): UiConfig
    {
        $ui = new UiConfig;

        if ($this->uiResolver !== null) {
            ($this->uiResolver)($ui);
        }

        return $ui;
    }
}
