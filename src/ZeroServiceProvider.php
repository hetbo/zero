<?php

namespace Hetbo\Zero;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Repositories\CarrotRepository;
// 1. IMPORT THE BLADE FACADE AND YOUR COMPONENT CLASS
use Illuminate\Support\Facades\Blade;
use Hetbo\Zero\View\Components\CarrotManager; // <-- Adjust namespace if needed
use Illuminate\Support\ServiceProvider;

class ZeroServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     */
    public function boot() {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'zero');

        // 2. ** ADD THIS LINE TO REGISTER THE COMPONENT **
        // This tells Laravel what <x-carrot-manager> means.
        Blade::component('carrot-manager', CarrotManager::class);

        if ($this->app->runningInConsole()) {
            // Your 'publishes' groups are fine.
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/zero'), // Suggest using 'zero' as the vendor name
            ], 'zero-views');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'zero-migrations');

            $this->publishes([
                __DIR__.'/../config/zero.php' => config_path('zero.php'),
            ], 'zero-config');
        }
    }

    /**
     * Register any application services.
     */
    public function register() {

        $this->mergeConfigFrom(__DIR__.'/../config/zero.php', 'zero');

        // Note: You have this bind call here and at the bottom. You only need one.
        $this->app->bind(CarrotRepositoryInterface::class, CarrotRepository::class);

        // This UserContract bind is from a previous version, but is fine to leave.
        $this->app->bind(UserContract::class, function ($app) {
            return $app->make(config('zero.user_model'));
        });
    }
}