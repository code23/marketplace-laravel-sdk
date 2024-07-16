<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchModules;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchAttributes;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchBlogCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCategories;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCharities;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCountries;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchCurrencies;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchFeaturedProducts;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchLatestPosts;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchSpecifications;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchTags;
use Code23\MarketplaceLaravelSDK\Jobs\MPEFetchVendors;

use Illuminate\Console\Command;

class MPECacheUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Key defaults to 'all'.
     *
     * @var string
     */
    protected $signature = 'mpe-cache:update {--key=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve and cache fresh data from the API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get passed in key (defaults to 'all')
        $key = $this->option('key');

        // available jobs
        $jobs = [
            'modules' => MPEFetchModules::class,
            'attributes' => MPEFetchAttributes::class,
            'blog_categories' => MPEFetchBlogCategories::class,
            'categories' => MPEFetchCategories::class,
            'charities' => MPEFetchCharities::class,
            'countries' => MPEFetchCountries::class,
            'currencies' => MPEFetchCurrencies::class,
            'featured_products' => MPEFetchFeaturedProducts::class,
            'latest_posts' => MPEFetchLatestPosts::class,
            'specifications' => MPEFetchSpecifications::class,
            'tags' => MPEFetchTags::class,
            'vendors' => MPEFetchVendors::class,
        ];

        // run each job
        foreach ($jobs as $jobKey => $jobClass) {
            if ($key == 'all' || $key == $jobKey) {
                try {
                    $job = new $jobClass($this);
                    $job->handle();
                } catch (\Throwable $th) {
                    $this->newLine();
                }
            }
        }
    }
}
