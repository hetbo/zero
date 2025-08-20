<?php

namespace Hetbo\Zero;

use Hetbo\Zero\Contracts\CarrotableRepositoryInterface;
use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Repositories\CarrotableRepository;
use Hetbo\Zero\Repositories\CarrotRepository;
// 1. IMPORT THE BLADE FACADE AND YOUR COMPONENT CLASS
use Illuminate\Support\Facades\Blade;
use Hetbo\Zero\View\Components\CarrotManager; // <-- Adjust namespace if needed
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'zero');

        Blade::component('zero::components.carrot','carrot');

        if ($this->app->runningInConsole()) {
            // Your 'publishes' groups are fine.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/zero'), // Suggest using 'zero' as the vendor name
            ], 'views');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'zero-migrations');

            $this->publishes([
                __DIR__.'/../config/zero.php' => config_path('zero.php'),
            ], 'config');
        }

/*        if (!file_exists(public_path('hetbo/zero/zero.umd.cjs'))) {
            $this->callAfterResolving('migrator', function () {
                $this->app['artisan']->call('vendor:publish', [
                    '--tag' => 'zero-assets',
                    '--force' => true
                ]);
            });
        }*/


        Route::get('hetbo/zero/{file}', function($file) {
            $path = __DIR__.'/../dist/' . $file;

            if (!file_exists($path) || !str_ends_with($file, '.cjs')) {
                abort(404);
            }

            return response()->file($path, [
                'Content-Type' => 'application/javascript',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        })->where('file', '.*\.cjs$');



    }

    /**
     * Register any application services.
     */
    public function register() {

        $this->mergeConfigFrom(__DIR__.'/../config/zero.php', 'zero');

        $this->app->bind(CarrotRepositoryInterface::class, CarrotRepository::class);
        $this->app->bind(CarrotableRepositoryInterface::class, CarrotableRepository::class);

    }
}