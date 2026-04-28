<?php

namespace Matheusmarnt\Scoutify\Support;

use Matheusmarnt\Scoutify\Concerns\Searchable;
use Symfony\Component\Finder\Finder;

final class TypeManifest
{
    public static function path(): string
    {
        return app()->bootstrapPath('cache/scoutify-types.php');
    }

    public static function load(): array
    {
        $path = self::path();

        return is_file($path) ? (array) require $path : [];
    }

    public static function build(): array
    {
        $paths = config('scoutify.discovery.paths', [app_path('Models')]);
        $manifest = [];

        foreach ($paths as $dir) {
            if (! is_dir($dir)) {
                continue;
            }
            $files = (new Finder)->in($dir)->files()->name('*.php');
            foreach ($files as $file) {
                $class = self::classFromFile($file->getRealPath());
                if ($class === null) {
                    continue;
                }
                try {
                    if (! class_exists($class)) {
                        continue;
                    }
                    $uses = self::collectTraits($class);
                    if (! in_array(Searchable::class, $uses, true)) {
                        continue;
                    }
                    $manifest[$class] = [
                        'key' => $class::globalSearchGroup(),
                        'label' => $class::globalSearchLabel(),
                        'icon' => $class::globalSearchIcon(),
                        'color' => $class::globalSearchColor(),
                    ];
                } catch (\Throwable) {
                    continue;
                }
            }
        }

        return $manifest;
    }

    public static function write(array $manifest): void
    {
        $path = self::path();
        $tmp = $path.'.tmp.'.getmypid();
        file_put_contents($tmp, '<?php return '.var_export($manifest, true).';'.PHP_EOL);
        rename($tmp, $path);
    }

    public static function forget(): void
    {
        $path = self::path();
        if (is_file($path)) {
            unlink($path);
        }
    }

    private static function classFromFile(string $file): ?string
    {
        $tokens = token_get_all((string) file_get_contents($file));
        $namespace = '';
        $class = '';

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                $i += 2;
                while (isset($tokens[$i]) && $tokens[$i] !== ';') {
                    $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
                    $i++;
                }
            }
            if ($tokens[$i][0] === T_CLASS) {
                $i += 2;
                $class = is_array($tokens[$i]) ? $tokens[$i][1] : '';
                break;
            }
        }

        return $namespace !== '' && $class !== '' ? $namespace.'\\'.$class : null;
    }

    private static function collectTraits(string $class): array
    {
        $traits = [];
        foreach (class_uses_recursive($class) as $trait) {
            $traits[] = $trait;
        }

        return $traits;
    }
}
