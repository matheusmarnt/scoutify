<?php

use Matheusmarnt\Scoutify\Services\Mutators\SearchableMethodStubVisitor;
use Matheusmarnt\Scoutify\Services\StubPlan;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

function traverseWithStubVisitor(string $code, StubPlan $plan): array
{
    $parser = (new ParserFactory)->createForNewestSupportedVersion();
    $stmts = $parser->parse($code);

    $visitor = new SearchableMethodStubVisitor($plan);
    $traverser = new NodeTraverser;
    $traverser->addVisitor($visitor);
    $newStmts = $traverser->traverse($stmts);

    return [
        'output' => (new Standard)->prettyPrintFile($newStmts),
        'visitor' => $visitor,
    ];
}

$plainClass = <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;

class User extends Model implements GloballySearchable
{
    use Searchable;
}
PHP;

it('injects globalSearchUrl method into class body', function () use ($plainClass) {
    $plan = new StubPlan(urlBody: "return route('users.show', \$this);");

    ['output' => $output, 'visitor' => $visitor] = traverseWithStubVisitor($plainClass, $plan);

    expect($output)->toContain('function globalSearchUrl()');
    expect($output)->toContain("route('users.show'");
    expect($visitor->addedUrlStub())->toBeTrue();
    expect($visitor->addedImports())->toBe([]);
});

it('injects resource import when urlImports provided', function () use ($plainClass) {
    $plan = new StubPlan(
        urlBody: "return UserResource::getUrl('view', ['record' => \$this]);",
        urlImports: ['App\Filament\Resources\UserResource'],
    );

    ['output' => $output, 'visitor' => $visitor] = traverseWithStubVisitor($plainClass, $plan);

    expect($output)->toContain('use App\Filament\Resources\UserResource;');
    expect($output)->toContain('UserResource::getUrl');
    expect($visitor->addedImports())->toBe(['App\Filament\Resources\UserResource']);
});

it('is idempotent when globalSearchUrl already declared', function () {
    $source = <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function globalSearchUrl(): string
    {
        return '/custom-url';
    }
}
PHP;

    $plan = new StubPlan(urlBody: "return route('users.show', \$this);");

    ['output' => $output, 'visitor' => $visitor] = traverseWithStubVisitor($source, $plan);

    expect($output)->toContain('/custom-url');
    expect(substr_count($output, 'function globalSearchUrl'))->toBe(1);
    expect($visitor->addedUrlStub())->toBeFalse();
});

it('does not add duplicate import when already present', function () use ($plainClass) {
    $sourceWithImport = str_replace(
        "use Matheusmarnt\Scoutify\Concerns\Searchable;",
        "use App\Filament\Resources\UserResource;\nuse Matheusmarnt\Scoutify\Concerns\Searchable;",
        $plainClass,
    );

    $plan = new StubPlan(
        urlBody: "return UserResource::getUrl('view', ['record' => \$this]);",
        urlImports: ['App\Filament\Resources\UserResource'],
    );

    ['output' => $output, 'visitor' => $visitor] = traverseWithStubVisitor($sourceWithImport, $plan);

    expect(substr_count($output, 'use App\Filament\Resources\UserResource;'))->toBe(1);
    expect($visitor->addedImports())->toBe([]);
});
