<?php

namespace Matheusmarnt\Scoutify\Services;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SearchableStubBuilder
{
    public static function make(): self
    {
        return new self;
    }

    public function buildFor(string $fqcn): StubPlan
    {
        $base = class_basename($fqcn);
        $plural = Str::plural($base);
        $snakePlural = Str::plural(Str::snake($base));

        foreach ($this->filamentCandidates($base, $plural) as $resource) {
            if (class_exists($resource)) {
                return new StubPlan(
                    urlBody: "return {$base}Resource::getUrl('view', ['record' => \$this]);",
                    urlImports: [$resource],
                );
            }
        }

        if (Route::has("{$snakePlural}.show")) {
            return new StubPlan(
                urlBody: "return route('{$snakePlural}.show', \$this);",
            );
        }

        if ($this->hasFolioPage($snakePlural)) {
            return new StubPlan(
                urlBody: "return url('/{$snakePlural}/'.\$this->getKey());",
            );
        }

        return new StubPlan(
            urlBody: "// TODO: customize URL for {$base} global search results\n        return url('/');",
        );
    }

    /** @return list<string> */
    private function filamentCandidates(string $base, string $plural): array
    {
        return [
            "App\\Filament\\Resources\\{$base}Resource",
            "App\\Filament\\Resources\\{$plural}\\{$base}Resource",
            "App\\Filament\\Resources\\{$base}s\\{$base}Resource",
            "App\\Filament\\Admin\\Resources\\{$base}Resource",
            "App\\Filament\\Admin\\Resources\\{$plural}\\{$base}Resource",
            "App\\Filament\\Clusters\\{$plural}\\Resources\\{$base}Resource",
        ];
    }

    private function hasFolioPage(string $snakePlural): bool
    {
        if (! class_exists(\Laravel\Folio\Folio::class)) {
            return false;
        }

        foreach (glob(resource_path("views/pages/{$snakePlural}/*.blade.php")) ?: [] as $file) {
            if (preg_match('/^\[.+\]\.blade\.php$/', basename($file))) {
                return true;
            }
        }

        return false;
    }
}
