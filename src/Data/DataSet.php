<?php

namespace Studiow\Laravel\Table\Data;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Studiow\Laravel\Table\Contracts\DataSet as DataSetInterface;

class DataSet
{
    public static function make($target): DataSetInterface
    {
        if ($target instanceof QueryBuilder) {
            return new QueryDataSet($target);
        }

        if ($target instanceof EloquentBuilder) {
            return new EloquentDataSet($target);
        }

        if ($target instanceof Model) {
            return new EloquentDataSet($target::query());
        }

        return new CollectionDataSet(new Collection($target));
    }
}
