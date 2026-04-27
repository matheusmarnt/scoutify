<?php

namespace Matheusmarnt\Scoutify\Services;

use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
use Matheusmarnt\Scoutify\Services\Mutators\SearchableMethodStubVisitor;
use Matheusmarnt\Scoutify\Services\Mutators\SearchableNodeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use RuntimeException;

class ModelSourceMutator
{
    public static function make(): self
    {
        return new self;
    }

    public function mutate(string $fqcn, ?StubPlan $stubPlan = null): ModelSourceMutation
    {
        $path = $this->resolveFilePath($fqcn);

        return $this->mutateFile($path, $stubPlan);
    }

    public function mutateFile(string $path, ?StubPlan $stubPlan = null): ModelSourceMutation
    {
        if (! is_file($path) || ! is_readable($path) || ! is_writable($path)) {
            throw new RuntimeException("Model source file is not accessible: {$path}");
        }

        $source = file_get_contents($path);

        if ($source === false) {
            throw new RuntimeException("Failed to read model source file: {$path}");
        }

        $parser = (new ParserFactory)->createForNewestSupportedVersion();
        $stmts = $parser->parse($source);

        if ($stmts === null) {
            throw new RuntimeException("Failed to parse model source: {$path}");
        }

        $visitor = new SearchableNodeVisitor(
            traitFqcn: Searchable::class,
            interfaceFqcn: GloballySearchable::class,
        );

        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor);
        $newStmts = $traverser->traverse($stmts);

        $stubVisitor = null;
        if ($stubPlan !== null) {
            $stubVisitor = new SearchableMethodStubVisitor($stubPlan);
            $stubTraverser = new NodeTraverser;
            $stubTraverser->addVisitor($stubVisitor);
            $newStmts = $stubTraverser->traverse($newStmts);
        }

        $mutation = new ModelSourceMutation(
            addedImports: array_merge(
                $visitor->addedImports(),
                $stubVisitor?->addedImports() ?? [],
            ),
            addedInterface: $visitor->addedInterface(),
            addedTraitUse: $visitor->addedTraitUse(),
            addedUrlStub: $stubVisitor?->addedUrlStub() ?? false,
        );

        if ($mutation->alreadyComplete()) {
            return $mutation;
        }

        $printed = (new Standard)->prettyPrintFile($newStmts);
        file_put_contents($path, $printed);

        return $mutation;
    }

    private function resolveFilePath(string $fqcn): string
    {
        if (! class_exists($fqcn)) {
            throw new RuntimeException("Class does not exist: {$fqcn}");
        }

        $path = (new ReflectionClass($fqcn))->getFileName();

        if (! $path) {
            throw new RuntimeException("Cannot resolve source file for: {$fqcn}");
        }

        return $path;
    }
}
