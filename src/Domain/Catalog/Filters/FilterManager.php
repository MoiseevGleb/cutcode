<?php

namespace Domain\Catalog\Filters;

class FilterManager
{
    public function __construct(
        protected array $filters = []
    ) {}

    public function registerFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    public function filters(): array
    {
        return $this->filters;
    }
}
