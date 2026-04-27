<?php

use Matheusmarnt\Scoutify\Support\GlobalSearchGroup;
use Matheusmarnt\Scoutify\Support\ResultDto;

it('constructs with correct properties', function () {
    $dto = new ResultDto('Title', null, '/', 'icon', 'g', 'Group', 'blue', '1');
    $group = new GlobalSearchGroup('articles', 'Articles', 'heroicon-o-document', 'blue', 1, [$dto]);

    expect($group->key)->toBe('articles')
        ->and($group->label)->toBe('Articles')
        ->and($group->total)->toBe(1)
        ->and($group->results)->toHaveCount(1);
});

it('implements Arrayable', function () {
    $dto = new ResultDto('Title', null, '/', 'icon', 'g', 'Group', 'blue', '1');
    $group = new GlobalSearchGroup('articles', 'Articles', 'heroicon-o-document', 'blue', 1, [$dto]);

    expect($group->toArray())->toHaveKeys(['key', 'label', 'icon', 'color', 'total', 'results']);
});
