<?php

namespace Hetbo\Zero\Console;

use Illuminate\Console\Command;

class InstallZero extends Command
{
    protected $signature = 'hetbo-zero:install';
    protected $description = 'Install Zero';

    public function handle()
    {

        $this->info('Installing Hetbo Zero ...');

        $this->info('Publishing Configuration...');

        if (! $this->configExists('zero.php')){
            $this->publishConfiguration();
            $this->info('Published Configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed Zero');

    }

    private function configExists($fileName)
    {
        return file_exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm('Config file already exists. Do you want to overwrite it?', false);
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Hetbo\Zero\ZeroServiceProvider",
            '--tag' => "config",
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }

}