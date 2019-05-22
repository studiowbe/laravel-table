<?php

namespace Studiow\Laravel\Table\Test;

use Collective\Html\HtmlServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Studiow\Laravel\Table\TableServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            HtmlServiceProvider::class,
            TableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
