<?php

namespace Studiow\Laravel\Table\Data;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Filter\CollectionFilter;
use Studiow\Laravel\Table\Contracts\DataSet;

class CollectionDataSet implements DataSet
{
    private $filter;

    public function __construct(Collection $target)
    {
        $this->filter = new CollectionFilter($target);
    }

    public function items(): Collection
    {
        return $this->filter->items();
    }

    public function orderBy(string $key, string $direction = 'asc')
    {
        $this->filter = new CollectionFilter($this->filter->items()->sortBy($key, SORT_REGULAR, $direction === 'desc'));

        return $this;
    }

    public function paginate(int $perPage = 15, string $pageName = 'page', ?int $page = null): LengthAwarePaginatorContract
    {
        $page = $page ?? (Paginator::resolveCurrentPage($pageName) ?? 1);

        return new LengthAwarePaginator(
            $this->items()->forPage($page, $perPage),
            $this->items()->count(),
            $perPage, $page
        );
    }

    public function where(string $key, $operator, $value = null, $boolean = 'and')
    {
        $this->filter = $this->filter->where($key, $operator, $value, $boolean);

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
