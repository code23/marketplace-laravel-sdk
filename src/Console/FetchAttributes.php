<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Code23\MarketplaceLaravelSDK\Jobs\v1\FetchAttributeData;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Console\Command;

class FetchAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpe:fetch-attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add fetching of attributes data to the queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle($params = [
        'with' => 'values',
        'is_active' => 1,
    ])
    {
        // as artisan command doesn't have a session, generate an auth token for the request
        $oauth = MPEAuthentication::authenticateSite();

        // dispatch the job
        $job_response = FetchAttributeData::dispatch($params, $oauth);

        // log a success message
        return $job_response ? $this->info('Attribute data fetching added to the queue') : $this->error('Something went wrong adding the attribute data fetch to the queue');
    }
}
