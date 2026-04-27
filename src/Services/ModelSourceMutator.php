<?php

namespace Matheusmarnt\Scoutify\Services;

use Matheusmarnt\Scoutify\Concerns\Searchable;
use Matheusmarnt\Scoutify\Contracts\GloballySearchable;
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

    public function mutate(string $fqcn): ModelSourceMutation
    {
        $path = $this->resolveFilePath($fqcn);

        return $this->mutateFile($path);
    }

    public function mutateFile(string $path): ModelSourceMutation
    {
        if (! is_file($path) || ! is_readable($path) || ! is_writable($path)) {
            throw new RuntimeException("Model source file is not accessible: {$path}");
        }

        $source = file_get_contents($path);

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

        $mutation = new ModelSourceMutation(
            addedImports: $visitor->addedImports(),
            addedInterface: $visitor->addedInterface(),
            addedTraitUse: $visitor->addedTraitUse(),
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
