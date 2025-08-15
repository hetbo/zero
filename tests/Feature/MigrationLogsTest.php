<?php

use Illuminate\Support\Facades\Schema;

it('runs the migration successfully', function () {
    // Load migrations from your package directory
    $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

    // Run migrations
    $this->artisan('migrate');

    // Check if table exists
    expect(Schema::hasTable('zero_logs'))->toBeTrue()
        ->and(Schema::hasColumn('zero_logs', 'id'))->toBeTrue()
        ->and(Schema::hasColumn('zero_logs', 'action'))->toBeTrue()
        ->and(Schema::hasColumn('zero_logs', 'data'))->toBeTrue()
        ->and(Schema::hasColumn('zero_logs', 'created_at'))->toBeTrue();

    // Check if columns exist
});