<?php

use Matheusmarnt\Scoutify\Services\ModelSourceMutation;

it('reports nothing changed when fully registered', function () {
    $m = new ModelSourceMutation(addedImports: [], addedInterface: false, addedTraitUse: false);

    expect($m->alreadyComplete())->toBeTrue();
    expect($m->summary())->toBe([]);
});

it('reports each added piece in summary', function () {
    $m = new ModelSourceMutation(
        addedImports: ['Matheusmarnt\\Scoutify\\Concerns\\Searchable'],
        addedInterface: true,
        addedTraitUse: true,
    );

    expect($m->alreadyComplete())->toBeFalse();
    expect($m->summary())->toBe([
        'Imported Matheusmarnt\\Scoutify\\Concerns\\Searchable',
        'Implemented GloballySearchable interface',
        'Added use Searchable; to class body',
    ]);
});
