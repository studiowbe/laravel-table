<?php

namespace Studiow\Laravel\Table\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Studiow\Laravel\Filtering\Filter\Filter;

interface DataSet extends Filter
{
    public function items(): Collection;

    public function orderBy(string $key, string $direction = 'asc');

    public function paginate(int $perPage = 15, string $pageName = 'page', ?int $page = null): LengthAwarePaginator;
}
