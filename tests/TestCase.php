<?php

namespace Hetbo\Zero\Tests;

use Hetbo\Zero\Tests\Models\TestModel;
use Hetbo\Zero\ZeroServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            ZeroServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('session.driver', 'array');

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

    }

    protected function createTestModel(): TestModel
    {
        return TestModel::create([
            'name' => 'Test Model',
            'email' => 'test@example.com',
        ]);
    }

}