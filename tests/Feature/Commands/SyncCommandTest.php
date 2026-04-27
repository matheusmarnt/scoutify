<?php

it('scoutify:sync calls flush then import', function () {
    config(['scoutify.types' => []]);
    $this->artisan('scoutify:sync')
        ->assertSuccessful();
});
