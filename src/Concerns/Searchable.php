<?php

namespace Matheusmarnt\Scoutify\Concerns;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Folio\Folio;
use Laravel\Scout\Builder;
use Laravel\Scout\ModelObserver;
use Laravel\Scout\Searchable as ScoutSearchable;
use Laravel\Scout\SearchableScope;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;

trait Searchable
{
    use ScoutSearchable;

    public function searchableAs()
    {
        $base = config('scout.prefix').$this->getTable();

        if (app()->environment('testing')) {
            return $base.'_testing';
        }

        return $base;
    }

    public static function bootSearchable(): void
    {
        static::addGlobalScope(new SearchableScope);

        $whenBootedCallback = function () {
            static::observe(new ModelObserver);
            (new static)->registerSearchableMacros();
        };

        if (method_exists(static::class, 'whenBooted')) {
            static::whenBooted($whenBootedCallback);
        } else {
            $whenBootedCallback();
        }

        if (app()->bound(GlobalSearchRegistry::class)) {
            app(GlobalSearchRegistry::class)->register(
                static::class,
                [
                    'key' => static::globalSearchGroup(),
                    'label' => static::globalSearchLabel(),
                    'icon' => static::globalSearchIcon(),
                    'color' => static::globalSearchColor(),
                ]
            );
        }
    }

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

        if (! $attr) {
            return null;
        }

        $value = $this->{$attr};

        if ($value === null || $value === '') {
            return null;
        }

        $text = (string) $value;

        return mb_strlen($text) > 150 ? mb_substr($text, 0, 147).'...' : $text;
    }

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
        // Auto-discover common description-like fields so match context
        // is visible when the search engine matched on a non-title field.
        foreach (['description', 'subtitle', 'excerpt', 'summary', 'bio', 'body'] as $field) {
            if (array_key_exists($field, $this->attributes)) {
                return $field;
            }
        }

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

    public function globalSearchBuilder(Builder $builder, string $query): Builder
    {
        return $builder;
    }

    public static function globalSearchLabel(): string
    {
        $key = 'scoutify::scoutify.types.'.Str::snake(class_basename(static::class)).'_plural';

        return Lang::has($key)
            ? __($key)
            : Str::plural(class_basename(static::class));
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
                }
            }
        }

        return null;
    }

    private function resolveFolioUrl(): ?string
    {
        if (! class_exists(Folio::class)) {
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
