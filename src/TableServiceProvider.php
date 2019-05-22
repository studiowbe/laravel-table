<?php

namespace Studiow\Laravel\Table;

use Carbon\Laravel\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'table');
    }
}
