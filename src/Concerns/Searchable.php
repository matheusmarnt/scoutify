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
}
