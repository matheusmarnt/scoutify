<?php

it('scoutify:install runs without error when given a driver option', function () {
    $this->artisan('scoutify:install', ['--driver' => 'meilisearch'])
        ->assertSuccessful();
});
