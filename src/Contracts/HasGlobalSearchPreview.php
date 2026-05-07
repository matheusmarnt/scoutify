<?php

namespace Matheusmarnt\Scoutify\Contracts;

use Matheusmarnt\Scoutify\Support\PreviewDto;

interface HasGlobalSearchPreview
{
    public function globalSearchPreview(): ?PreviewDto;
}
