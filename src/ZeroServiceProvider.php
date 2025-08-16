<?php

namespace Hetbo\Zero;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Repositories\CarrotRepository;
use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider {
    public function boot() {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'zero');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/media-manager'),
            ], 'zero-views');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'zero-migrations');
        }
    }

    public function register() {

        $this->mergeConfigFrom(__DIR__.'/../config/zero.php', 'zero');

        $this->app->bind(CarrotRepositoryInterface::class, CarrotRepository::class);

        $this->app->bind(UserContract::class, function ($app) {
            return $app->make(config('zero.user_model'));
        });

/*        $this->app->bind('calculator', function ($app) {
            return new Calculator();
        });*/

        if ($this->app->runningInConsole()) {
/*            $this->commands(
                [
                    InstallZero::class,
                ]
            );*/

            $this->publishes([
                __DIR__.'/../config/zero.php' => config_path('zero.php'),
            ], 'zero-config'); // The tag here must match the tag in your command

/*            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');*/

        }

        $this->app->bind(
            CarrotRepositoryInterface::class,
            CarrotRepository::class
        );
    }
}