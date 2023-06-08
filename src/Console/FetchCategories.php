<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Code23\MarketplaceLaravelSDK\Jobs\v1\FetchCategoryData;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Console\Command;

class FetchCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpe:fetch-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add fetching of category data to the queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle($params = [
            'with' => 'images,active_children_categories.images',
            'is_null' => 'top_id',
            'is_active' => true,
        ])
    {
        // as artisan command doesn't have a session, generate an auth token for the request
        $oauth = MPEAuthentication::authenticateSite();

        // dispatch the job
        $job_response = FetchCategoryData::dispatch($params, $oauth);

        // log a success message
        return $job_response ? $this->info('Category data fetching added to the queue') : $this->error('Something went wrong adding the category data fetch to the queue');
    }
}
