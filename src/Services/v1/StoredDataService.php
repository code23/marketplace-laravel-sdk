<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEAttributes;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECategories;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECurrencies;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPESpecifications;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPETags;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPEVendors;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class StoredDataService extends Service
{
    /**
     * Retrieve stored MPE data
     * @param string $key The name of the cache key to retrieve
     * @param array $params Optional - parameters to pass to the API
     */
    public function retrieve($key, ...$params) {
        // storage time
        $seconds = config('marketplace-laravel-sdk.cache.'.$key.'.minutes') * 60;

        // get key from cache or retrieve data and save it
        $data = Cache::remember($key, $seconds, function () use ($key, $params) {

            switch ($key) {
                case 'attributes':
                    return $this->retrieveAttributes(...$params);
                    break;

                case 'categories':
                    return $this->retrieveCategories(...$params);
                    break;

                case 'currencies':
                    return $this->retrieveCurrencies(...$params);
                    break;

                case 'specifications':
                    return $this->retrieveSpecifications(...$params);
                    break;

                case 'tags':
                    return $this->retrieveTags(...$params);
                    break;

                case 'vendors':
                    return $this->retrieveVendors(...$params);
                    break;

                default:
                    # code...
                    break;
            }

        });

        // or return null
        return $data ? $data->toArray() : null;
    }

    private function retrieveAttributes(
        $params = [
            'with' => 'values',
        ]
    ) {
        try {
            // get the categories from API
            return MPEAttributes::list($params);

        } catch (Exception $e) {
            if(config('app.env') == 'production' && env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving attributes from API_");
            Log::error($e);

            return false;
        }
    }

    /**
     * Retrieve nested categories and children
     */
    private function retrieveCategories($params = [
        'with' => 'images,active_children_categories.images',
        'is_null' => 'top_id',
        'is_active' => true,
    ]) {
        try {
            // get the categories from API
            return MPECategories::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving categories from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveCurrencies($params = [
        'is_enabled' => true,
    ]) {
        try {
            // get the categories from API
            return MPECurrencies::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving currencies from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveSpecifications($params = [
        'with' => 'values',
    ]) {
        try {
            // get the categories from API
            return MPESpecifications::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving specifications from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveTags($params = []) {
        try {
            // get the categories from API
            return MPETags::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving tags from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveVendors($params = [
        'is_onboarded' => true,
    ]) {
        try {
            // get the categories from API
            return MPEVendors::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.url') . "* StoredDataService.php: _Error retrieving vendors from API_");
            Log::error($e);

            return false;
        }
    }
}
