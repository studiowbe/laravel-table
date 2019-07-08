<?php

namespace Studiow\Laravel\Table\Behaviour;

use Collective\Html\FormFacade;

trait HasActions
{
    private $actions = [];
    private $selectOn = 'id';
    private $hasSelectAll = false;
    private $actionsLabel;

    public function addAction(string $id, string $label)
    {
        $this->actions[$id] = $label;

        return $this;
    }

    public function hasActions(): bool
    {
        return count($this->actions) > 0;
    }

    public function selectOn(string $key)
    {
        $this->selectOn = $key;

        return $this;
    }

    public function actions(): array
    {
        return $this->actions;
    }

    public function hasSelectAll(): bool
    {
        return $this->hasSelectAll;
    }

    public function withSelectAll(bool $selectAll = true)
    {
        $this->hasSelectAll = $selectAll;

        return $this;
    }

    public function actionsLabel(string $label)
    {
        $this->actionsLabel = $label;

        return $this;
    }

    public function selectAction(?string $emptyText = null)
    {
        $emptyText = $emptyText ?? ($this->actionsLabel ?? __('Choose action'));

        return implode('', [
            FormFacade::select('action', array_merge(['' => $emptyText], $this->actions), $this->request->query('action')),
        ]);
    }

    public function checkboxForRow($row)
    {
        return FormFacade::checkbox('sel[]', data_get($row, $this->selectOn), null, ['class' => 'row-select']);
    }

    public function checkboxForHeader()
    {
        if ($this->hasSelectAll()) {
            return FormFacade::checkbox('select_all', 'all', null, ['class' => 'row-select-all']);
        }

        return '&nbsp;';
    }
}
