<?php

use Matheusmarnt\Scoutify\Support\ResultDto;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('creates dto with all fields', function () {
    $dto = new ResultDto(
        title: 'Hello',
        subtitle: 'World',
        url: '/hello',
        icon: 'heroicon-o-star',
        group: 'articles',
        groupLabel: 'Articles',
        groupColor: 'blue',
        modelKey: '1',
    );

    expect($dto->title)->toBe('Hello')
        ->and($dto->subtitle)->toBe('World')
        ->and($dto->modelKey)->toBe('1');
});

it('toArray returns all keys', function () {
    $dto = new ResultDto(
        title: 'Hello',
        subtitle: null,
        url: '/hello',
        icon: 'heroicon-o-star',
        group: 'articles',
        groupLabel: 'Articles',
        groupColor: 'blue',
    );

    expect($dto->toArray())->toHaveKeys([
        'title', 'subtitle', 'url', 'icon', 'group', 'groupLabel', 'groupColor', 'modelKey',
    ])->and($dto->toArray()['subtitle'])->toBeNull()
        ->and($dto->toArray()['modelKey'])->toBeNull();
});

it('fromModel creates dto from GloballySearchable model', function () {
    $model = new Article(['name' => 'Test Article']);
    $model->id = 42;

    $dto = ResultDto::fromModel(
        model: $model,
        url: '/articles/42',
        groupLabel: 'Articles',
        modelKey: '42',
    );

    expect($dto->title)->toBe('Test Article')
        ->and($dto->subtitle)->toBeNull()
        ->and($dto->url)->toBe('/articles/42')
        ->and($dto->icon)->toBe('heroicon-o-document')
        ->and($dto->group)->toBe('articles')
        ->and($dto->groupLabel)->toBe('Articles')
        ->and($dto->groupColor)->toBe('blue')
        ->and($dto->modelKey)->toBe('42');
});

it('fromModel uses globalSearchGroup when groupLabel is empty', function () {
    $model = new Article(['name' => 'Test']);
    $model->id = 1;

    $dto = ResultDto::fromModel(model: $model, url: '/');

    expect($dto->groupLabel)->toBe('articles');
});
