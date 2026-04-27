<?php

it('scoutify:install runs without error when given a driver option', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->assertSuccessful();
});

it('scoutify:install fails with an unknown driver', function () {
    $this->artisan('scoutify:install', ['--driver' => 'unknown'])
        ->assertFailed();
});
