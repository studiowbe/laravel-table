<?php

namespace Studiow\Laravel\Table\Data;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Filter\QueryFilter;
use Studiow\Laravel\Table\Contracts\DataSet;

class QueryDataSet implements DataSet
{
    private $filter;

    public function __construct(Builder $builder)
    {
        $this->filter = new QueryFilter($builder);
    }

    public function items(): Collection
    {
        return $this->filter->items();
    }

    public function orderBy(string $key, string $direction = 'asc')
    {
        $this->filter->query()->orderBy($key, $direction);
    }

    public function paginate(int $perPage = 15, string $pageName = 'page', ?int $page = null): LengthAwarePaginator
    {
        return $this->filter->query()->paginate($perPage, ['*'], $pageName, $page);
    }

    public function where(string $key, $operator, $value = null, $boolean = 'and')
    {
        $this->filter->where($key, $operator, $value, $boolean);

        return $this;
    }

    public function andWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'and');
    }

    public function orWhere(string $key, $operator, $value = null)
    {
        return $this->where($key, $operator, $value, 'or');
    }
}
