<?php

it('result-row renders href and wire:navigate for a given url', function () {
    $html = (string) view('scoutify::components.gs.result-row', [
        'id' => 'result-0',
        'url' => '/users/42',
        'icon' => 'heroicon-o-user',
        'titleHtml' => 'John Doe',
        'subtitleHtml' => null,
        'index' => 0,
        'rememberQuery' => 'john',
    ]);

    expect($html)
        ->toContain('href="/users/42"')
        ->toContain('wire:navigate');
});
