<?php

namespace Matheusmarnt\Scoutify\Services\Mutators;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeVisitorAbstract;

final class SearchableNodeVisitor extends NodeVisitorAbstract
{
    /** @var list<string> */
    private array $addedImports = [];

    private bool $addedInterface = false;

    private bool $addedTraitUse = false;

    private string $traitShortName;

    private string $interfaceShortName;

    public function __construct(
        private readonly string $traitFqcn,
        private readonly string $interfaceFqcn,
    ) {
        $this->traitShortName = $this->shortName($traitFqcn);
        $this->interfaceShortName = $this->shortName($interfaceFqcn);
    }

    public function enterNode(Node $node): ?Node
    {
        if ($node instanceof Namespace_) {
            $this->ensureImports($node);
        }

        if ($node instanceof Class_) {
            $this->ensureImplements($node);
            $this->ensureTraitUse($node);
        }

        return null;
    }

    private function ensureImports(Namespace_ $ns): void
    {
        $existing = [];
        foreach ($ns->stmts as $stmt) {
            if ($stmt instanceof Use_) {
                foreach ($stmt->uses as $u) {
                    $existing[] = $u->name->toString();
                }
            }
        }

        $needed = [];
        foreach ([$this->traitFqcn, $this->interfaceFqcn] as $fqcn) {
            if (! in_array($fqcn, $existing, true)) {
                $needed[] = $fqcn;
            }
        }

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

    private function ensureImplements(Class_ $class): void
    {
        foreach ($class->implements as $impl) {
            $name = $impl->toString();
            if ($name === $this->interfaceFqcn || $name === $this->interfaceShortName) {
                return;
            }
        }

        $class->implements[] = new Name($this->interfaceShortName);
        $this->addedInterface = true;
    }

    private function ensureTraitUse(Class_ $class): void
    {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $t) {
                    $name = $t->toString();
                    if ($name === $this->traitFqcn || $name === $this->traitShortName) {
                        return;
                    }
                }
            }
        }

        array_unshift(
            $class->stmts,
            new TraitUse([new Name($this->traitShortName)]),
        );
        $this->addedTraitUse = true;
    }

    private function shortName(string $fqcn): string
    {
        $parts = explode('\\', $fqcn);

        return end($parts);
    }

    /** @return list<string> */
    public function addedImports(): array
    {
        return $this->addedImports;
    }

    public function addedInterface(): bool
    {
        return $this->addedInterface;
    }

    public function addedTraitUse(): bool
    {
        return $this->addedTraitUse;
    }
}
