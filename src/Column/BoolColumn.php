<?php

namespace Studiow\Laravel\Table\Column;

class BoolColumn extends Column
{
    private $label_true;
    private $label_false;

    public function __construct(string $id, string $label, bool $sortable = false, int $index = 0)
    {
        parent::__construct($id, $label, $sortable, $index);

        $this->setTrueLabel(__('Yes'))->setFalseLabel(__('No'));
    }

    public function setTrueLabel(string $label)
    {
        $this->label_true = $label;

        return $this;
    }

    public function setFalseLabel(string $label)
    {
        $this->label_false = $label;

        return $this;
    }

    public function resolve($row)
    {
        $value = parent::resolve($row);

        return (bool) $value ? $this->label_true : $this->label_false;
    }
}
