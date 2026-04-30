<?php

use BladeUI\Icons\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Matheusmarnt\Scoutify\Services\SearchAggregator;
use Matheusmarnt\Scoutify\Support\GlobalSearchGroup;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;
use Matheusmarnt\Scoutify\Tests\Fixtures\Models\RiService;

beforeEach(function () {
    Schema::create('ri_services', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->timestamps();
    });

    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('body')->nullable();
        $table->timestamps();
    });
});

it('GlobalSearchGroup icon is not mangled when model returns ri-* from globalSearchIcon()', function () {
    $dir = sys_get_temp_dir().'/scoutify-ri-'.uniqid();
    mkdir($dir, 0755, true);
    app(Factory::class)->add('remix', ['prefix' => 'ri', 'paths' => [$dir]]);

    RiService::create(['name' => 'Customer Service']);

    $aggregator = new SearchAggregator([RiService::class => []]);
    $groups = $aggregator->search('Customer');

    $group = $groups->first();

    expect($group)->toBeInstanceOf(GlobalSearchGroup::class)
        ->and($group->icon)->toBe('ri-customer-service-2-fill');
});

it('GlobalSearchGroup icon keeps heroicon prefix for own-prefixed icons', function () {
    Article::create(['name' => 'Test Article']);

    $aggregator = new SearchAggregator([Article::class => []]);
    $groups = $aggregator->search('Test');

    $group = $groups->first();

    expect($group)->toBeInstanceOf(GlobalSearchGroup::class)
        ->and($group->icon)->toBe('heroicon-o-document');
});
