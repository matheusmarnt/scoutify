<?php

use Matheusmarnt\Scoutify\Support\ResultDto;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\AlreadyRegistered;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

it('ResultDto groupColor reflects model globalSearchColor override', function () {
    $model = new Article(['name' => 'Test Article']);
    $model->id = 1;

    $dto = ResultDto::fromModel(model: $model, url: '/articles/1', modelKey: '1');

    expect($dto->groupColor)->toBe('blue');
});

it('ResultDto groupColor defaults to gray when model returns default color', function () {
    $model = new AlreadyRegistered(['name' => 'Test']);
    $model->id = 1;

    $dto = ResultDto::fromModel(model: $model, url: '/items/1', modelKey: '1');

    expect($dto->groupColor)->toBe('gray');
});
