<?php

namespace Studiow\Laravel\Table\Column;

use Studiow\Laravel\Table\Contracts\Column as ColumnInterface;

class Column implements ColumnInterface
{
    private $id;
    private $label;
    private $index;
    private $resolver;
    private $sortable;

    public function __construct(string $id, string $label, bool $sortable = false, int $index = 0)
    {
        $this->id = $id;
        $this->label($label)
            ->index($index)
            ->sortable($sortable);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    public function index(int $index)
    {
        $this->index = $index;

        return $this;
    }

    public function sortable(bool $isSortable = true)
    {
        $this->sortable = $isSortable;

        return $this;
    }

    public function resolveWith(?callable $resolver = null)
    {
        $this->resolver = $resolver;

        return $this;
    }

    public function resolve($row)
    {
        $value = data_get($row, $this->id);

        if (! is_null($this->resolver)) {
            $value = call_user_func($this->resolver, $value, $row);
        }

        return $value;
    }

    public static function create(string $id, string $label, bool $sortable = false, int $index = 0)
    {
        return new static($id, $label, $sortable, $index);
    }
}
