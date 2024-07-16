<?php

namespace Code23\MarketplaceLaravelSDK\Jobs;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECharities;
use Code23\MarketplaceLaravelSDK\Services\AuthenticationService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class MPEFetchCharities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $command;

    /**
    * The number of times the job may be attempted.
    *
    * @var int
    */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(Command $command = null)
    {
        $this->command = $command;
    }

    /**
    * Calculate the number of seconds to wait before retrying the job.
    *
    * @return array<int, int>
    */
    public function backoff(): array
    {
        return [5, 10, 20];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // check for required module
        if(Cache::get('modules')->contains('charities')) {

            // check for slack alert suitability
            $slack = config('app.env') != 'local' && config('boilerplate.slack.webhook_url');

            if($this->command) $this->command->line('Fetching charities…');

            // We must authenticate the site manually because there is no user session, as we are running from a job / the command line…
            // Create an instance of the authentication service
            $authenticationService = new AuthenticationService();
            // Get the oAuth token
            $oauth = $authenticationService->authenticateSite();

            // If the oAuth token request failed
            if(!$oauth) {

                // report failure
                if($this->command) {
                    $this->command->error('Auth failed');
                } else {
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchCharities: _Auth failed_");
                }

                Log::alert('MPEFetchCharities: Auth failed');

                throw new Exception('MPEFetchCharities: MPE Auth failed');
            }

            // get params from config
            $params = config('boilerplate.mpe_cache.charities.params');

            try {
                // get charities from API
                $charities = MPECharities::list($params, $oauth);

                // if none returned
                if(! count($charities) && $this->command) {
                    $this->command->error('Charities data empty');
                }
            } catch (Exception $e) {

                // report failure
                if($this->command) {
                    $this->command->error('Retrieval error: ' . $e->getMessage());
                } else {
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchCharities: _" . $e->getMessage() . "_");
                }

                Log::alert('MPEFetchCharities: Retrieval error - ' . $e);

                // fail the job
                throw new Exception('MPEFetchCharities - ' . $e->getMessage());
            }

            // update cached data
            Cache::put('charities', $charities);

            if($this->command) {
                $this->command->info('Cached charities updated');
                $this->command->newLine();
            }
        }
    }
}
