<?php

namespace Matheusmarnt\Scoutify\Services;

final class ScoutConfigurator
{
    /**
     * Check if the given model class file already uses Matheusmarnt\Scoutify\Concerns\Searchable.
     */
    public static function isAlreadySearchable(string $fqcn): bool
    {
        $file = self::resolveFilePath($fqcn);

        if ($file === null || ! file_exists($file)) {
            return false;
        }

        $contents = file_get_contents($file);

        return str_contains($contents, 'Matheusmarnt\\Scoutify\\Concerns\\Searchable')
            || str_contains($contents, 'use Scoutify\\Concerns\\Searchable');
    }

    /**
     * Attempt to resolve the source file path from a FQCN.
     * Works for PSR-4 classes registered in the autoloader.
     */
    public static function resolveFilePath(string $fqcn): ?string
    {
        try {
            $reflector = new \ReflectionClass($fqcn);
            $file = $reflector->getFileName();

            return $file !== false ? $file : null;
        } catch (\ReflectionException) {
            return null;
        }
    }
}
