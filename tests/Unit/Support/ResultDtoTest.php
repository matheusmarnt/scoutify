<?php

use Matheusmarnt\Scoutify\Support\ResultDto;

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
