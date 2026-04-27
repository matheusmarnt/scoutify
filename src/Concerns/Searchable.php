<?php

namespace Matheusmarnt\Scoutify\Concerns;

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
     * Models should override this method to return the correct resource URL.
     */
    public function globalSearchUrl(): string
    {
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
}
