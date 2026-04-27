<?php

namespace Matheusmarnt\Scoutify\Contracts;

interface GloballySearchable
{
    public function toGloballySearchableArray(): array;

    public static function globalSearchGroup(): string;

    public static function globalSearchIcon(): string;

    public static function globalSearchColor(): string;
}
