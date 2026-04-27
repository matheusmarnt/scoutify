<?php

namespace Matheusmarnt\Scoutify\Services\Mutators;

use Matheusmarnt\Scoutify\Services\StubPlan;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

final class SearchableMethodStubVisitor extends NodeVisitorAbstract
{
    private bool $addedUrlStub = false;

    /** @var list<string> */
    private array $addedImports = [];

    public function __construct(
        private readonly StubPlan $stubPlan,
    ) {}

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Namespace_) {
            $this->ensureImports($node);
        }

        if ($node instanceof Class_) {
            $this->ensureMethod($node);
        }

        return null;
    }

    private function ensureImports(Namespace_ $ns): void
    {
        if ($this->stubPlan->urlImports === []) {
            return;
        }

        $existing = [];
        foreach ($ns->stmts as $stmt) {
            if ($stmt instanceof Use_) {
                foreach ($stmt->uses as $u) {
                    $existing[] = $u->name->toString();
                }
            }
        }

        $needed = array_values(array_filter(
            $this->stubPlan->urlImports,
            fn (string $fqcn) => ! in_array($fqcn, $existing, true),
        ));

        if ($needed === []) {
            return;
        }

        $newUses = array_map(
            fn (string $fqcn) => new Use_([new UseUse(new Name($fqcn))]),
            $needed,
        );

        $insertAt = 0;
        foreach ($ns->stmts as $i => $stmt) {
            if ($stmt instanceof Use_) {
                $insertAt = $i + 1;
            }
        }

        array_splice($ns->stmts, $insertAt, 0, $newUses);
        $this->addedImports = $needed;
    }

    private function ensureMethod(Class_ $class): void
    {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $stmt->name->toString() === 'globalSearchUrl') {
                return;
            }
        }

        $parser = (new ParserFactory)->createForNewestSupportedVersion();
        $bodyStmts = $parser->parse("<?php\n".$this->stubPlan->urlBody) ?? [];

        $builder = (new BuilderFactory)
            ->method('globalSearchUrl')
            ->makePublic()
            ->setReturnType('string');

        foreach ($bodyStmts as $stmt) {
            $builder->addStmt($stmt);
        }

        $class->stmts[] = $builder->getNode();
        $this->addedUrlStub = true;
    }

    public function addedUrlStub(): bool
    {
        return $this->addedUrlStub;
    }

    /** @return list<string> */
    public function addedImports(): array
    {
        return $this->addedImports;
    }
}
