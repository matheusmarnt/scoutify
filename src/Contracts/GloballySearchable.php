<?php

namespace Matheusmarnt\Scoutify\Contracts;

interface GloballySearchable
{
    public function toGloballySearchableArray(): array;

    public function globalSearchTitle(): string;

    public function globalSearchSubtitle(): ?string;

    /**
     * Detail-page URL used as the result row's anchor `href`.
     *
     * Rendered with `wire:navigate`, so a regular HTTP URL is expected.
     * Return `url('/')` or `'#'` when the model has no public detail page.
     */
    public function globalSearchUrl(): string;

    public static function globalSearchGroup(): string;

    public static function globalSearchIcon(): string;

    public static function globalSearchColor(): string;

    public function globalSearchBuilder(\Laravel\Scout\Builder $builder, string $query): \Laravel\Scout\Builder;
}
