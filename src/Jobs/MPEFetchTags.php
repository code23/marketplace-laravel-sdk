<?php

namespace Code23\MarketplaceLaravelSDK\Jobs;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECache;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPETags;
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

class MPEFetchTags implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $command;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3;

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
        if(MPECache::get('modules')->contains('tags')) {

            // check for slack alert suitability
            $slack = config('app.env') != 'local' && config('boilerplate.slack.webhook_url');

            if($this->command) $this->command->line('Fetching tags…');

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
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchTags: _Auth failed_");
                }

                Log::alert('MPEFetchTags: Auth failed');

                throw new Exception('MPEFetchTags: MPE Auth failed');
            }

            // get params from config
            $params = config('boilerplate.mpe_cache.tags.params');

            try {
                // get tags from API
                $tags = MPETags::list($params, $oauth);

                // if none returned
                if(! count($tags) && $this->command) {
                    $this->command->error('Tags data empty');
                }
            } catch (Exception $e) {

                // report failure
                if($this->command) {
                    $this->command->error('Retrieval error: ' . $e->getMessage());
                } else {
                    if ($slack) SlackAlert::message('*' . config('app.url') . "* MPEFetchTags: _" . $e->getMessage() . "_");
                }

                Log::alert('MPEFetchTags: Retrieval error - ' . $e);

                // fail the job
                throw new Exception('MPEFetchTags - ' . $e->getMessage());
            }

            // update cached data
            Cache::put('tags', $tags);

            if($this->command) {
                $this->command->info('Cached tags updated');
                $this->command->newLine();
            }

        } else {

            if($this->command) {
                $this->command->line('Module "tags" not active');
                $this->command->newLine();
            }

        }
    }
}
