<?php

it('scoutify:import succeeds with no types configured', function () {
    config(['scoutify.types' => []]);
    $this->artisan('scoutify:import')
        ->assertSuccessful();
});
