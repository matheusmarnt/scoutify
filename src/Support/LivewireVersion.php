<?php

namespace Matheusmarnt\Scoutify\Support;

use Composer\InstalledVersions;

final class LivewireVersion
{
    public static function major(): int
    {
        if (! InstalledVersions::isInstalled('livewire/livewire')) {
            return 0;
        }

        $version = InstalledVersions::getVersion('livewire/livewire');

        return (int) explode('.', ltrim((string) $version, 'v'))[0];
    }

    public static function isV4OrAbove(): bool
    {
        return self::major() >= 4;
    }
}
