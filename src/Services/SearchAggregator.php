<?php

namespace Matheusmarnt\Scoutify\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Laravel\Scout\Builder;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
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
     * Search across all registered types and return ResultDto[].
     *
     * @return array<ResultDto>
     */
    public function search(string $query, int $limit = 10, bool $onlyActive = false, bool $includeTrashed = false): array
    {
        if (blank($query)) {
            return [];
        }

        $iconResolver = IconResolver::make();
        $results = [];

        foreach ($this->types as $modelClass => $meta) {
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

            $label = $meta['label'] ?? class_basename($modelClass);
            $icon = $meta['icon'] ?? (is_a($modelClass, GloballySearchable::class, true)
                ? $modelClass::globalSearchIcon()
                : 'heroicon-o-magnifying-glass');
            $color = $meta['color'] ?? (is_a($modelClass, GloballySearchable::class, true)
                ? $modelClass::globalSearchColor()
                : 'gray');

            foreach ($models as $model) {
                if ($model instanceof GloballySearchable) {
                    $dto = ResultDto::fromModel(
                        model: $model,
                        url: $model->globalSearchUrl(),
                        groupLabel: $label,
                        modelKey: (string) $model->getKey(),
                    );
                } else {
                    $dto = new ResultDto(
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

                $results[] = $dto;
            }
        }

        return $results;
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
