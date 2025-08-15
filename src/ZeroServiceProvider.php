<?php

namespace Hetbo\Zero;

use Hetbo\Zero\Console\InstallZero;
use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider {
    public function boot() {

    }

    public function register() {

        $this->mergeConfigFrom(__DIR__.'/../config/zero.php', 'zero');

        $this->app->bind('calculator', function ($app) {
            return new Calculator();
        });

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    InstallZero::class,
                ]
            );

            $this->publishes([
                __DIR__.'/../config/zero.php' => config_path('zero.php'),
            ], 'config'); // The tag here must match the tag in your command


        }
    }
}