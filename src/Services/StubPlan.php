<?php

namespace Matheusmarnt\Scoutify\Services;

final class StubPlan
{
    /**
     * @param  list<string>  $urlImports  FQCNs to add as `use` imports alongside the stub.
     */
    public function __construct(
        public readonly string $urlBody,
        public readonly array $urlImports = [],
    ) {}
}
