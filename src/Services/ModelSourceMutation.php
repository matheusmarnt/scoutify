<?php

namespace Matheusmarnt\Scoutify\Services;

final class ModelSourceMutation
{
    /**
     * @param  list<string>  $addedImports  FQCNs newly added as `use` imports.
     */
    public function __construct(
        public readonly array $addedImports,
        public readonly bool $addedInterface,
        public readonly bool $addedTraitUse,
    ) {}

    public function alreadyComplete(): bool
    {
        return $this->addedImports === [] && ! $this->addedInterface && ! $this->addedTraitUse;
    }

    /**
     * @return list<string>
     */
    public function summary(): array
    {
        $lines = [];

        foreach ($this->addedImports as $fqcn) {
            $lines[] = "Imported {$fqcn}";
        }

        if ($this->addedInterface) {
            $lines[] = 'Implemented GloballySearchable interface';
        }

        if ($this->addedTraitUse) {
            $lines[] = 'Added use Searchable; to class body';
        }

        return $lines;
    }
}
