<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class ErrorService extends Service
{
    /**
     * Alert via Slack, log, etc.
     */
    public function handle(String $filename, String $message, Exception $e = null) {
        // die and dump if debug is true and exception passed in
        if (env('APP_DEBUG') && $e) return dd($e);

        // generate a random error code
        $errorCode = 'ERR-' . str_random(8);

        // log the error
        Log::error($errorCode . ' | ' . $filename . ' | ' . $message . ' | ' . $e);

        // send a Slack alert if the webhook is set
        if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* " . $errorCode . " | " . $filename . " | _" . $message . "_");

        // return the error code
        return $errorCode;
    }
}
