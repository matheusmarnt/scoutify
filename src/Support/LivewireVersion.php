<?php

namespace Matheusmarnt\Scoutify\Support;

use Composer\InstalledVersions;

/**
 * Detects the installed Livewire major version at runtime.
 *
 * Returns 0 when Livewire is not installed, version is unknown (path repo),
 * or version string starts with 'dev-'.
 */
final class LivewireVersion
{
    public static function major(): int
    {
        if (! InstalledVersions::isInstalled('livewire/livewire')) {
            return 0; // @codeCoverageIgnore
        }

        $version = InstalledVersions::getVersion('livewire/livewire');

        if ($version === null || str_starts_with($version, 'dev-')) {
            return 0; // @codeCoverageIgnore
        }

        return (int) explode('.', ltrim($version, 'v'))[0];
    }

    public static function isV4OrAbove(): bool
    {
        return self::major() >= 4;
    }
}
