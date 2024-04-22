<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchModules;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchAttributes;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchBlogCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCharities;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCountries;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCurrencies;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchSpecifications;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchTags;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchVendors;

use Illuminate\Console\Command;

class MPECacheUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpe-cache:update {--key=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve fresh cache data from the API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->option('key');

        /**
         * Do this one first, as it's required for module checks in later jobs
         */
        if($key == 'all' || $key == 'modules') {
            $job = new MPEFetchModules($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'attributes') {
            $job = new MPEFetchAttributes($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'blog_categories') {
            $job = new MPEFetchBlogCategories($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'categories') {
            $job = new MPEFetchCategories($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'charities') {
            $job = new MPEFetchCharities($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'countries') {
            $job = new MPEFetchCountries($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'currencies') {
            $job = new MPEFetchCurrencies($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'specifications') {
            $job = new MPEFetchSpecifications($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'tags') {
            $job = new MPEFetchTags($this);
            $job->handle();
        }

        if($key == 'all' || $key == 'vendors') {
            $job = new MPEFetchVendors($this);
            $job->handle();
        }
    }
}
