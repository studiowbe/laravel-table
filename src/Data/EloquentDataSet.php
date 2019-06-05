<?php

namespace Studiow\Laravel\Table\Data;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Filter\EloquentFilter;
use Studiow\Laravel\Table\Contracts\DataSet;

class EloquentDataSet implements DataSet
{
    private $filter;

    public function __construct(Builder $builder)
    {
        $this->filter = new EloquentFilter($builder);
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
