<?php

namespace Studiow\Laravel\Table\Contracts;

use Illuminate\Http\Request;
use Studiow\Laravel\Filtering\Filter\Filter as FilterTarget;

interface Filter
{
    public function apply($value, FilterTarget $filter): FilterTarget;

    public function getId(): string;

    public function getLabel(): string;

    public function getIndex(): int;

    public function view(Request $request);
}
