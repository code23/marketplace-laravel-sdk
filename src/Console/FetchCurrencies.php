<?php

namespace Code23\MarketplaceLaravelSDK\Console;

use Code23\MarketplaceLaravelSDK\Jobs\v1\FetchCurrencyData;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Console\Command;

class FetchCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mpe:fetch-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add fetching of currencies data to the queue';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle($params = [
        'is_enabled' => true,
    ])
    {
        // as artisan command doesn't have a session, generate an auth token for the request
        $oauth = MPEAuthentication::authenticateSite();

        // dispatch the job
        $job_response = FetchCurrencyData::dispatch($params, $oauth);

        // log a success message
        return $job_response ? $this->info('Currencies data fetching added to the queue') : $this->error('Something went wrong adding the currency data fetch to the queue');
    }
}
