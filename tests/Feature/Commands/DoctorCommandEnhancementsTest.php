<?php

use Matheusmarnt\Scoutify\Tests\Fixtures\Models\Article;

beforeEach(function () {
    config([
        'scout.driver' => 'collection',
    ]);
});

it('reports invalid class in scoutify types', function () {
    config([
        'scoutify.types' => ['App\\NonExistentModel' => ['label' => 'Test']],
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('does not exist')
        ->assertExitCode(1);
});

it('reports class that does not implement GloballySearchable', function () {
    config([
        'scoutify.types' => [stdClass::class => ['label' => 'Test']],
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('does not implement GloballySearchable')
        ->assertExitCode(1);
});

it('passes for valid GloballySearchable types', function () {
    config([
        'scoutify.types' => [Article::class => ['label' => 'Articles']],
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('implement GloballySearchable')
        ->assertExitCode(0);
});

it('warns when no types configured', function () {
    config([
        'scoutify.types' => [],
    ]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('No types configured');
});

it('warns when @livewireScripts not found', function () {
    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('@livewireScripts');
});

it('warns about queue config in production when disabled', function () {
    app()->detectEnvironment(function () {
        return 'production';
    });

    config(['scout.queue' => false]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('SCOUT_QUEUE');
});

it('confirms queue enabled when active', function () {
    config(['scout.queue' => true]);

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('Scout queue enabled');
});

it('passes livewire scripts check when @livewireScripts present in layout', function () {
    $layoutDir = resource_path('views/layouts');
    $layoutFile = $layoutDir.'/app.blade.php';
    @mkdir($layoutDir, 0755, true);
    file_put_contents($layoutFile, '<html><body>@livewireScripts</body></html>');

    $this->artisan('scoutify:doctor')
        ->expectsOutputToContain('@livewireScripts found in layout');

    @unlink($layoutFile);
    @rmdir($layoutDir);
});
