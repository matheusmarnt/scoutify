<?php

it('result-row renders href and wire:navigate for internal url', function () {
    $html = (string) view('scoutify::components.gs.result-row', [
        'id' => 'result-0',
        'url' => '/users/42',
        'icon' => 'heroicon-o-user',
        'titleHtml' => 'John Doe',
        'subtitleHtml' => null,
        'index' => 0,
        'rememberQuery' => 'john',
        'linkTarget' => 'navigate',
    ]);

    expect($html)
        ->toContain('href="/users/42"')
        ->toContain('wire:navigate')
        ->not->toContain('target=');
});

it('result-row renders target=_blank and rel for external url with linkTarget=_blank', function () {
    $html = (string) view('scoutify::components.gs.result-row', [
        'url' => 'https://external.example.com/page',
        'icon' => 'heroicon-o-user',
        'titleHtml' => 'External Page',
        'subtitleHtml' => null,
        'index' => 0,
        'linkTarget' => '_blank',
    ]);

    expect($html)
        ->toContain('href="https://external.example.com/page"')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"')
        ->not->toContain('wire:navigate');
});

it('result-row defense: forces _blank when linkTarget=navigate but url host is external', function () {
    $html = (string) view('scoutify::components.gs.result-row', [
        'url' => 'https://external.example.com/page',
        'icon' => 'heroicon-o-user',
        'titleHtml' => 'External Page',
        'subtitleHtml' => null,
        'index' => 0,
        'linkTarget' => 'navigate',  // wrong, but defense-in-depth corrects it
    ]);

    expect($html)
        ->not->toContain('wire:navigate')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"');
});

it('result-row renders plain link with _self target (no wire:navigate, no _blank)', function () {
    $html = (string) view('scoutify::components.gs.result-row', [
        'url' => '/users/42',
        'icon' => 'heroicon-o-user',
        'titleHtml' => 'User',
        'subtitleHtml' => null,
        'index' => 0,
        'linkTarget' => '_self',
    ]);

    expect($html)
        ->toContain('href="/users/42"')
        ->not->toContain('wire:navigate')
        ->not->toContain('target="_blank"');
});
