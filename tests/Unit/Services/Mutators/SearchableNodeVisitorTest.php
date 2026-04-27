<?php

use Matheusmarnt\Scoutify\Services\Mutators\SearchableNodeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

function runVisitor(string $source): array
{
    $parser = (new ParserFactory)->createForNewestSupportedVersion();
    $stmts = $parser->parse($source);

    $visitor = new SearchableNodeVisitor(
        traitFqcn: 'Matheusmarnt\\Scoutify\\Concerns\\Searchable',
        interfaceFqcn: 'Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable',
    );

    $traverser = new NodeTraverser;
    $traverser->addVisitor($visitor);
    $stmts = $traverser->traverse($stmts);

    return [
        'code' => (new Standard)->prettyPrintFile($stmts),
        'addedImports' => $visitor->addedImports(),
        'addedInterface' => $visitor->addedInterface(),
        'addedTraitUse' => $visitor->addedTraitUse(),
    ];
}

it('adds imports, implements, and trait use on a plain class', function () {
    $source = file_get_contents(__DIR__.'/../../../Fixtures/Models/Plain.php');
    $result = runVisitor($source);

    expect($result['addedImports'])->toEqualCanonicalizing([
        'Matheusmarnt\\Scoutify\\Concerns\\Searchable',
        'Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable',
    ]);
    expect($result['addedInterface'])->toBeTrue();
    expect($result['addedTraitUse'])->toBeTrue();
    expect($result['code'])->toContain('use Matheusmarnt\\Scoutify\\Concerns\\Searchable;');
    expect($result['code'])->toContain('use Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable;');
    expect($result['code'])->toContain('class Plain extends Model implements GloballySearchable');
    expect($result['code'])->toMatch('/class Plain[^\{]+\{\s*use Searchable;/');
});

it('tops up only what is missing on a partially-registered class', function () {
    $source = file_get_contents(__DIR__.'/../../../Fixtures/Models/PartiallyRegistered.php');
    $result = runVisitor($source);

    expect($result['addedImports'])->toEqual([
        'Matheusmarnt\\Scoutify\\Contracts\\GloballySearchable',
    ]);
    expect($result['addedInterface'])->toBeTrue();
    expect($result['addedTraitUse'])->toBeFalse();
});

it('is a no-op on an already-registered class', function () {
    $source = file_get_contents(__DIR__.'/../../../Fixtures/Models/AlreadyRegistered.php');
    $result = runVisitor($source);

    expect($result['addedImports'])->toBe([]);
    expect($result['addedInterface'])->toBeFalse();
    expect($result['addedTraitUse'])->toBeFalse();
});

it('preserves PHP 8 attributes on the class', function () {
    $source = file_get_contents(__DIR__.'/../../../Fixtures/Models/WithAttributes.php');
    $result = runVisitor($source);

    expect($result['code'])->toContain('#[\\AllowDynamicProperties]');
    expect($result['code'])->toContain('class WithAttributes extends Model implements GloballySearchable');
});
