<?php

namespace Matheusmarnt\Scoutify\Contracts;

use Matheusmarnt\Scoutify\Authorization\VisibilityRule;

interface HasGlobalSearchVisibility
{
    public function globalSearchVisibility(): VisibilityRule;
}
