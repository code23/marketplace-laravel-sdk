<?php

namespace Code23\MarketplaceSDK\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'marketplace-sdk:install';

    /**
     * console command description
     *
     * @var string
     */
    protected $description = 'Install all of the Marketplace SDK resources';

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
            '--tag' => 'mpe-config',
            '--force' => true
        ]);

        // config
        $this->comment('Publishing form requests...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'mpe-requests',
            '--force' => true
        ]);

        // view components
        $this->comment('Publishing view components...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'mpe-view-components',
            '--force' => true
        ]);

        // views
        $this->comment('Publishing views...');
        $this->callSilent('vendor:publish', [
            '--tag' => 'mpe-views',
            '--force' => true
        ]);

        // prompt user
        $this->info('Marketplace SDK installed successfully.');
    }
}
