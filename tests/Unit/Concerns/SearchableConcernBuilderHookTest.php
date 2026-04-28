<?php

use Laravel\Scout\Builder;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

// SearchableConcernBuilderHookTest: default hook returns same builder unchanged
it('globalSearchBuilder default returns same builder instance', function () {
    $model = new Article;
    $builder = new Builder($model, 'test');

    $result = $model->globalSearchBuilder($builder, 'test');

    expect($result)->toBe($builder);
});

it('globalSearchBuilder is overridable in child model', function () {
    $model = new Article;

    expect(method_exists($model, 'globalSearchBuilder'))->toBeTrue();
});
