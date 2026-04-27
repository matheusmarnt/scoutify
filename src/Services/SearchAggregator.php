<?php

namespace Matheusmarnt\Scoutify\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Laravel\Scout\Builder;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Support\GlobalSearchGroup;
use Matheusmarnt\Scoutify\Support\GlobalSearchRegistry;
use Matheusmarnt\Scoutify\Support\ResultDto;

final class SearchAggregator
{
    /** @param array<string, array{icon?: string, color?: string, label?: string}> $types */
    public function __construct(private readonly array $types = []) {}

    public static function make(?array $types = null): self
    {
        return new self($types ?? config('scoutify.types', []));
    }

    /**
     * Search across all registered types and return a Collection of GlobalSearchGroup.
     *
     * @return Collection<int, GlobalSearchGroup>
     */
    public function search(string $query, int $limit = 10, bool $onlyActive = false, bool $includeTrashed = false): Collection
    {
        if (blank($query)) {
            return collect();
        }

        $iconResolver = IconResolver::make();

        // Merge registry entries with config-supplied types; config wins on overlap.
        $registryTypes = app()->bound(GlobalSearchRegistry::class)
            ? app(GlobalSearchRegistry::class)->all()
            : [];

        $types = $registryTypes;
        foreach ($this->types as $modelClass => $configMeta) {
            if (isset($types[$modelClass])) {
                $types[$modelClass] = array_merge($types[$modelClass], $configMeta);
            } else {
                $types[$modelClass] = $configMeta;
            }
        }

        $groups = [];

        foreach ($types as $modelClass => $meta) {
            if (! class_exists($modelClass)) {
                continue;
            }

            if (! is_subclass_of($modelClass, Model::class)) {
                continue;
            }

            try {
                /** @var Builder $builder */
                $builder = $modelClass::search($query)->take($limit);

                // Apply trashed scope if model supports SoftDeletes
                if ($includeTrashed && in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
                    $builder->withTrashed();
                }

                $models = $builder->get();
            } catch (\Throwable) {
                continue;
            }

            $models = $models->filter(fn ($record) => $this->canView($record))->values();

            // Apply onlyActive filter at collection level if model has an `active` column convention
            if ($onlyActive && method_exists($modelClass, 'scopeActive')) {
                $models = $models->filter(fn ($m) => (bool) ($m->active ?? true));
            }

            if ($models->isEmpty()) {
                continue;
            }

            $label = $meta['label'] ?? class_basename($modelClass);
            $icon = $meta['icon'] ?? (is_a($modelClass, GloballySearchable::class, true)
                ? $modelClass::globalSearchIcon()
                : 'heroicon-o-magnifying-glass');
            $color = $meta['color'] ?? (is_a($modelClass, GloballySearchable::class, true)
                ? $modelClass::globalSearchColor()
                : 'gray');
            $key = $meta['key'] ?? $meta['group'] ?? class_basename($modelClass);

            $dtos = [];
            foreach ($models as $model) {
                if ($model instanceof GloballySearchable) {
                    $dtos[] = ResultDto::fromModel(
                        model: $model,
                        url: $model->globalSearchUrl(),
                        groupLabel: $label,
                        modelKey: (string) $model->getKey(),
                    );
                } else {
                    $dtos[] = new ResultDto(
                        title: (string) ($model->name ?? $model->title ?? $model->getKey()),
                        subtitle: null,
                        url: url('/'),
                        icon: $iconResolver->resolve($icon),
                        group: class_basename($modelClass),
                        groupLabel: $label,
                        groupColor: $color,
                        modelKey: (string) $model->getKey(),
                    );
                }
            }

            $groups[] = new GlobalSearchGroup(
                key: $key,
                label: $label,
                icon: $iconResolver->resolve($icon),
                color: $color,
                total: count($dtos),
                results: $dtos,
            );
        }

        return collect($groups);
    }

    private function canView(mixed $record): bool
    {
        try {
            $hasPolicy = Gate::getPolicyFor($record) !== null;
            $hasAbility = Gate::has('view');

            if (! $hasPolicy && ! $hasAbility) {
                return true;
            }

            return Gate::check('view', $record);
        } catch (\Throwable) {
            return true;
        }
    }
}
