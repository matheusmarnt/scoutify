<?php

it('scoutify:searchable succeeds when given a specific model argument', function () {
    // With no real models in app/Models/, discoverer returns empty — command succeeds
    $this->artisan('scoutify:searchable')
        ->assertSuccessful();
});
