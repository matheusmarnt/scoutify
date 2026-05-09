<?php

it('scoutify:sync calls flush then import', function () {
    $this->artisan('scoutify:sync')
        ->assertSuccessful();
});
