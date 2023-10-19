<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class ErrorService extends Service
{
    /**
     * Alert via Slack, log, etc.
     */
    public function report(String $filename, String $message) {
        Log::error($filename . ': ' . $message);
        if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* " . $filename . ": _" . $message . "_");
    }
}
