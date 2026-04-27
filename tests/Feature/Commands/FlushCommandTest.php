<?php

it('scoutify:flush succeeds with no types configured', function () {
    config(['scoutify.types' => []]);
    $this->artisan('scoutify:flush')
        ->assertSuccessful();
});
