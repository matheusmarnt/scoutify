<?php

namespace Matheusmarnt\Scoutify\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

final class ModelDiscoverer
{
    public function __construct(private readonly string $modelsPath = '') {}

    public static function make(?string $basePath = null): self
    {
        return new self($basePath ?? app_path('Models'));
    }

    /**
     * Discover all Eloquent model FQCNs in the configured path.
     *
     * @return array<string>
     */
    public function discover(): array
    {
        $path = $this->modelsPath;

        if (! is_dir($path)) {
            return [];
        }

        $models = [];

        foreach ((new Finder)->files()->name('*.php')->in($path) as $file) {
            $fqcn = $this->fileToFqcn($file->getRealPath(), $path);

            if ($fqcn !== null && $this->isEloquentModel($fqcn)) {
                $models[] = $fqcn;
            }
        }

        return $models;
    }

    private function fileToFqcn(string $filePath, string $basePath): ?string
    {
        $relative = Str::of($filePath)
            ->after($basePath.DIRECTORY_SEPARATOR)
            ->replaceLast('.php', '')
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->toString();

        return 'App\\Models\\'.$relative;
    }

    private function isEloquentModel(string $fqcn): bool
    {
        if (! class_exists($fqcn)) {
            return false;
        }

        return is_subclass_of($fqcn, Model::class);
    }
}
