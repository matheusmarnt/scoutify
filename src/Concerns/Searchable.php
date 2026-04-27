<?php

namespace Matheusmarnt\Scoutify\Concerns;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable as ScoutSearchable;

trait Searchable
{
    use ScoutSearchable;

    public function toGloballySearchableArray(): array
    {
        return [
            'title' => $this->globalSearchTitle(),
            'subtitle' => $this->globalSearchSubtitle(),
            'url' => $this->globalSearchUrl(),
        ];
    }

    public function globalSearchTitle(): string
    {
        return (string) ($this->{$this->globalSearchTitleAttribute()} ?? '');
    }

    public function globalSearchSubtitle(): ?string
    {
        $attr = $this->globalSearchSubtitleAttribute();

        return $attr ? (string) $this->{$attr} : null;
    }

    /**
     * Returns the URL for this model's global search result.
     *
     * Resolves automatically via: Filament resource → named route → Folio page → url('/').
     * Override this method in your model to provide a custom URL.
     */
    public function globalSearchUrl(): string
    {
        $filamentUrl = $this->resolveFilamentResourceUrl();
        if ($filamentUrl !== null) {
            return $filamentUrl;
        }

        $route = Str::plural(Str::snake(class_basename(static::class))).'.show';
        if (Route::has($route)) {
            return route($route, $this);
        }

        $folioUrl = $this->resolveFolioUrl();
        if ($folioUrl !== null) {
            return $folioUrl;
        }

        return url('/');
    }

    protected function globalSearchTitleAttribute(): string
    {
        return 'name';
    }

    protected function globalSearchSubtitleAttribute(): ?string
    {
        return null;
    }

    public static function globalSearchGroup(): string
    {
        return class_basename(static::class);
    }

    public static function globalSearchIcon(): string
    {
        return 'heroicon-o-magnifying-glass';
    }

    public static function globalSearchColor(): string
    {
        return 'gray';
    }

    private function resolveFilamentResourceUrl(): ?string
    {
        $base = class_basename(static::class);
        $plural = Str::plural($base);

        $candidates = [
            "App\\Filament\\Resources\\{$base}Resource",
            "App\\Filament\\Resources\\{$plural}\\{$base}Resource",
            "App\\Filament\\Resources\\{$base}s\\{$base}Resource",
            "App\\Filament\\Admin\\Resources\\{$base}Resource",
            "App\\Filament\\Admin\\Resources\\{$plural}\\{$base}Resource",
            "App\\Filament\\Clusters\\{$plural}\\Resources\\{$base}Resource",
        ];

        foreach ($candidates as $resource) {
            if (class_exists($resource) && method_exists($resource, 'getUrl')) {
                try {
                    return $resource::getUrl('view', ['record' => $this]);
                } catch (\Throwable) {
                    // Resource exists but URL generation failed — try next
                }
            }
        }

        return null;
    }

    private function resolveFolioUrl(): ?string
    {
        if (! class_exists(\Laravel\Folio\Folio::class)) {
            return null;
        }

        $segment = Str::plural(Str::snake(class_basename(static::class)));

        foreach (glob(resource_path("views/pages/{$segment}/*.blade.php")) ?: [] as $file) {
            if (preg_match('/^\[.+\]\.blade\.php$/', basename($file))) {
                return url("/{$segment}/".$this->getKey());
            }
        }

        return null;
    }
}
