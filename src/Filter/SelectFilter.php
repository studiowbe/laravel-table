<?php

namespace Studiow\Laravel\Table\Filter;

use Collective\Html\FormFacade;
use Illuminate\Http\Request;
use Studiow\Laravel\Filtering\Filter\Filter as FilterTarget;
use Studiow\Laravel\Table\Contracts\Filter;

class SelectFilter implements Filter
{
    private $id;
    private $label;
    private $index;
    private $options;

    public function __construct(string $id, string $label, array $options, int $index = 0)
    {
        $this->id = $id;
        $this->label = $label;
        $this->index = $index;
        $this->options = $options;
    }

    public function apply($value, FilterTarget $filter): FilterTarget
    {
        if (array_key_exists($value, $this->options) && strlen($value)>0) {
            $filter = $filter->where($this->getId(), '=', $value);
        }

        return $filter;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function view(Request $request)
    {
        return implode('', [
            FormFacade::label($this->id, $this->getLabel()),
            FormFacade::select($this->id, $this->options, $request->get($this->getId())),
        ]);
    }
}
