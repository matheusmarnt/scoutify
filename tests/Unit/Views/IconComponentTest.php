<?php

use BladeUI\Icons\Factory;
use BladeUI\Icons\IconsManifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;

beforeEach(function () {
    Artisan::call('view:clear');
});

it('passes through remix icon when ri prefix is registered', function () {
    $tmpDir = sys_get_temp_dir().'/scoutify-ri-'.uniqid();
    mkdir($tmpDir, 0755, true);
    file_put_contents("$tmpDir/customer-service-2-fill.svg", '<svg xmlns="http://www.w3.org/2000/svg"></svg>');

    app(Factory::class)->add('remix', ['prefix' => 'ri', 'paths' => [$tmpDir]]);

    $html = Blade::render('<x-scoutify::gs.icon name="ri-customer-service-2-fill" />');

    expect($html)->toContain('<svg');

    @unlink("$tmpDir/customer-service-2-fill.svg");
    @rmdir($tmpDir);
});

it('adds default prefix to unqualified short name', function () {
    $html = Blade::render('<x-scoutify::gs.icon name="magnifying-glass" />');

    expect($html)->toContain('<svg');
});

it('passes through already-qualified heroicon name', function () {
    $html = Blade::render('<x-scoutify::gs.icon name="heroicon-o-star" />');

    expect($html)->toContain('<svg');
});

it('passes through tabler icon when tabler prefix is registered', function () {
    $tmpDir = sys_get_temp_dir().'/scoutify-tabler-'.uniqid();
    mkdir($tmpDir, 0755, true);
    file_put_contents("$tmpDir/home.svg", '<svg xmlns="http://www.w3.org/2000/svg"></svg>');

    app(Factory::class)->add('tabler', ['prefix' => 'tabler', 'paths' => [$tmpDir]]);

    $html = Blade::render('<x-scoutify::gs.icon name="tabler-home" />');

    expect($html)->toContain('<svg');

    @unlink("$tmpDir/home.svg");
    @rmdir($tmpDir);
});

it('adds default prefix to icon with unknown prefix', function () {
    expect(fn () => Blade::render('<x-scoutify::gs.icon name="foo-bar" />'))
        ->toThrow(ViewException::class, 'o-foo-bar');
});

it('falls back to default prefix when no icon sets are registered', function () {
    $emptyFactory = new Factory(
        app(Filesystem::class),
        app(IconsManifest::class),
    );
    $this->app->instance(Factory::class, $emptyFactory);

    expect(fn () => Blade::render('<x-scoutify::gs.icon name="ri-home" />'))
        ->toThrow(ViewException::class, 'heroicon-o-ri-home');
});
