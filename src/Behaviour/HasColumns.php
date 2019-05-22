<?php

namespace Studiow\Laravel\Table\Behaviour;

use Studiow\Laravel\Table\Contracts\Column;

trait HasColumns
{
    private $columns = [];

    public function addColumn(Column $column)
    {
        $this->columns[$column->getId()] = $column;
    }

    public function hasColumn(string $id): bool
    {
        return array_key_exists($id, $this->columns);
    }

    public function column(string $id): ?Column
    {
        return $this->hasColumn($id) ? $this->columns[$id] : null;
    }

    public function columns(): array
    {
        uasort($this->columns, function (Column $a, Column $b) {
            return $a->getIndex() <=> $b->getIndex();
        });

        return $this->columns;
    }
}
