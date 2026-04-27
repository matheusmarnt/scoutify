<?php

namespace Matheusmarnt\Scoutify\Contracts;

interface GloballySearchable
{
    public function toGloballySearchableArray(): array;

    public function globalSearchTitle(): string;
    public function globalSearchSubtitle(): ?string;
    public function globalSearchUrl(): string;

    public static function globalSearchGroup(): string;
    public static function globalSearchIcon(): string;
    public static function globalSearchColor(): string;
}
