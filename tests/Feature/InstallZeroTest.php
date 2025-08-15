<?php

use Hetbo\Zero\Console\InstallZero;
use Illuminate\Support\Facades\File;

it('displays installation start message when config does not exist', function () {
    // Ensure config doesn't exist to avoid confirmation
    if (File::exists(config_path('zero.php'))) {
        unlink(config_path('zero.php'));
    }

    $this->artisan('hetbo-zero:install')
        ->expectsOutput('Installing Hetbo Zero ...')
        ->expectsOutput('Installed Zero');
});

it('publishes config when config does not exist', function () {
    if (File::exists(config_path('zero.php'))) {
        unlink(config_path('zero.php'));
    }

    $this->artisan('hetbo-zero:install')
        ->expectsOutput('Publishing Configuration...')
        ->expectsOutput('Published Configuration')
        ->expectsOutput('Installed Zero');

    expect(File::exists(config_path('zero.php')))->toBeTrue();
});

it('asks to overwrite when config exists and user says no', function () {
    // Create config file first
    File::put(config_path('zero.php'), '<?php return [];');

    $this->artisan('hetbo-zero:install')
        ->expectsConfirmation('Config file already exists. Do you want to overwrite it?', 'no')
        ->expectsOutput('Existing configuration was not overwritten')
        ->expectsOutput('Installed Zero');
});

it('overwrites config when user confirms', function () {
    // Create config file first
    File::put(config_path('zero.php'), '<?php return [];');

    $this->artisan('hetbo-zero:install')
        ->expectsConfirmation('Config file already exists. Do you want to overwrite it?', 'yes')
        ->expectsOutput('Overwriting configuration file...')
        ->expectsOutput('Installed Zero');
});

it('handles missing config directory gracefully', function () {
    // Ensure config file doesn't exist to avoid confirmation dialog
    if (File::exists(config_path('zero.php'))) {
        unlink(config_path('zero.php'));
    }

    $this->artisan('hetbo-zero:install')
        ->expectsOutput('Installing Hetbo Zero ...')
        ->assertExitCode(0);
});