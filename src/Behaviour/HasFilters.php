<?php

namespace Studiow\Laravel\Table\Behaviour;

use Studiow\Laravel\Table\Contracts\Filter;

trait HasFilters
{
    private $filters = [];

    public function addFilter(Filter $filter)
    {
        $this->filters[$filter->getId()] = $filter;
    }

    public function hasFilter(string $id): bool
    {
        return array_key_exists($id, $this->filters);
    }

    public function filter(string $id): ?Filter
    {
        return $this->hasFilter($id) ? $this->filters[$id] : null;
    }

    public function filters(): array
    {
        uasort($this->filters, function (Filter $a, Filter $b) {
            return $a->getIndex() <=> $b->getIndex();
        });

        return $this->filters;
    }

    public function hasFilters(): bool
    {
        return count($this->filters) > 0;
    }
}
