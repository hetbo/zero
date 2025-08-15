<?php

namespace Hetbo\Zero;

use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider {
    public function boot() {

    }

    public function register() {
        $this->app->bind('calculator', function ($app) {
            return new Calculator();
        });
    }
}