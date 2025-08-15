<?php

namespace Hetbo\Zero\Tests;

use Hetbo\Zero\ZeroServiceProvider;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    protected function setUp(): void
    {
        parent::setUp();
        // additional setup
//        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            ZeroServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
// Set up a default session driver for the tests.
        // The 'array' driver doesn't need any file paths, which solves the error.
        $app['config']->set('session.driver', 'array');

        // You can also set up a default database connection for tests
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set up file storage for testing
        config()->set('filesystems.disks.public.driver', 'local');
        config()->set('filesystems.disks.public.root', storage_path('framework/testing/disks/public'));

    }

/*    function createTestFile($name = 'test.txt', $content = 'test content'): File
    {
        return UploadedFile::fake()->createWithContent($name, $content);
    }

    function createTestImage($name = 'test.jpg'): File
    {
        return UploadedFile::fake()->image($name);
    }*/

}