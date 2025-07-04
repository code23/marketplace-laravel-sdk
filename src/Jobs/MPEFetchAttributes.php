<?php

namespace Code23\MarketplaceLaravelSDK\Jobs;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEAttributes;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECache;
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

class MPEFetchAttributes implements ShouldQueue
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
        if(MPECache::get('modules')->contains('product-attributes')) {

            // check for slack alert suitability
            $slack = config('app.env') != 'local' && config('boilerplate.slack.webhook_url');

            if($this->command) $this->command->line('Fetching attributes…');

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
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchAttributes: _Auth failed_");
                }

                Log::alert('MPEFetchAttributes: Auth failed');

                throw new Exception('MPEFetchAttributes: MPE Auth failed');
            }

            // get params from config
            $params = config('boilerplate.mpe_cache.attributes.params');

            try {
                // get attributes from API
                $attributes = MPEAttributes::list($params, $oauth);

                // if no attributes returned
                if(! count($attributes) && $this->command) {
                    $this->command->error('Attributes data empty');
                }
            } catch (Exception $e) {

                // report failure
                if($this->command) {
                    $this->command->error('Retrieval error: ' . $e->getMessage());
                } else {
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchAttributes: _" . $e->getMessage() . "_");
                }

                Log::alert('MPEFetchAttributes: Retrieval error - ' . $e);

                // fail the job
                throw new Exception('MPEFetchAttributes - ' . $e->getMessage());
            }

            // update cached data
            Cache::put('attributes', $attributes);

            if($this->command) {
                $this->command->info('Cached attributes updated');
                $this->command->newLine();
            }
        } else {
            if($this->command) {
                $this->command->line('Module "product-attributes" not active');
                $this->command->newLine();
            }
        }
    }
}
