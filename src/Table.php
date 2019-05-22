<?php

namespace Studiow\Laravel\Table;

use Illuminate\Http\Request;
use Studiow\Laravel\Table\Behaviour\CanPaginate;
use Studiow\Laravel\Table\Behaviour\HasActions;
use Studiow\Laravel\Table\Behaviour\HasColumns;
use Studiow\Laravel\Table\Behaviour\HasFilters;
use Studiow\Laravel\Table\Column\Column;
use Studiow\Laravel\Table\Contracts\Filter;
use Studiow\Laravel\Table\Data\DataSet;

class Table
{
    use HasColumns, HasFilters, CanPaginate, HasActions;

    private $data;

    private $defaultOrder = '';
    private $defaultOrderDirection = 'asc';
    private $request;

    public function __construct($target, array $columns = [], array $filters = [])
    {
        $this->data = DataSet::make($target);
        array_map([$this, 'addColumn'], $columns);
        array_map([$this, 'addFilter'], $filters);
        $this->request = request();
    }

    public function rows(?Request $request = null)
    {
        $request = $request ?? request();

        return $this->data($request)->map(function ($row) {
            return $this->getRow($row);
        });
    }

    public function withRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function defaultOrderBy(string $orderBy, string $order = 'asc')
    {
        $this->defaultOrder = $orderBy;
        $this->defaultOrderDirection = $order;
        return $this;
    }

    public function results(?Request $request = null)
    {
        $request = $request ?? $this->request;

        array_map(function (Filter $filter) use ($request) {
            $filter->apply($request->query($filter->getId()), $this->data);
        }, $this->filters);

        $order = $request->query('order', $this->defaultOrderDirection) === 'desc' ? 'desc' : 'asc';
        $orderBy = $request->query('orderBy', $this->defaultOrder);

        if ($this->hasColumn($orderBy) && $this->column($orderBy)->isSortable()) {
            $this->data->orderBy($orderBy, $order);
        }

        return $this->data;
    }

    public function data(?Request $request = null)
    {
        if ($this->isPaginated()) {
            return collect($this->paginator($request)->items());
        }

        return collect($this->results()->items()->all());
    }

    public function headers(?Request $request = null)
    {
        $request = $request ?? $this->request;

        $headers = array_map(function (Column $column) use ($request) {
            return $this->getHeader($column, $request);
        }, $this->columns());
        if ($this->hasActions()) {
            array_unshift($headers, $this->checkboxForHeader());
        }

        return $headers;
    }

    private function getRow($row)
    {
        $cells = array_map(function (Column $column) use ($row) {
            return $column->resolve($row);
        }, $this->columns());

        if ($this->hasActions()) {
            array_unshift($cells, $this->checkboxForRow($row));
        }

        return $cells;
    }

    private function getHeader(Column $column, Request $request)
    {
        $inner = $column->getLabel();
        if (!$column->isSortable()) {
            return $inner;
        }

        $params = $request->query();
        $params['orderBy'] = $column->getId();
        $params['order'] = 'asc';

        if ($request->query('orderBy', $this->defaultOrder) === $column->getId()) {
            $params['order'] = $request->query('order', 'asc') === 'asc' ? 'desc' : 'asc';
        }

        $class = 'sortable sort-' . $params['order'];
        unset($params['page']);

        return sprintf('<a href="?%s" class="%s">%s</a>', http_build_query($params), $class, $inner);
    }

    public function view(array $data = [], string $view = 'table::table')
    {
        return view($view, $data)->with(['table' => $this]);
    }

    public function getFilters()
    {
        return array_map(function (Filter $filter) {
            return $filter->view($this->request);
        }, $this->filters());
    }

    public function needsForm(): bool
    {
        return $this->hasActions() || $this->hasFilters();
    }
}
