<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'marketplace-laravel-sdk:install';

    /**
     * console command description
     *
     * @var string
     */
    protected $description = 'Install all of the Marketplace Laravel SDK resources';

    /**
     * execute the console command
     *
     * @return void
     */
    public function handle()
    {
        // config
        $this->comment('Publishing config...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'marketplace-laravel-sdk-config',
        ]);

        // controllers
        $this->comment('Publishing controllers...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'marketplace-laravel-sdk-controllers'
        ]);

        // models
        $this->comment('Publishing models...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'marketplace-laravel-sdk-models',
        ]);

        // view components
        $this->comment('Publishing view components...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'marketplace-laravel-sdk-view-components',
        ]);

        // views
        $this->comment('Publishing views...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'marketplace-laravel-sdk-views',
        ]);

        // prompt user
        $this->info('Marketplace Laravel SDK installed successfully.');
    }
}
