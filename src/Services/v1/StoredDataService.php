<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEAttributes;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECategories;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPECurrencies;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPESpecifications;
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
     * @param string $string The name of the cache key to retrieve
     */
    public function retrieve($string) {
        // storage time
        $seconds = config('marketplace-laravel-sdk.cache.'.$string.'.minutes') * 60;

        // get key from cache or retrieve data and save it
        $data = Cache::remember($string, $seconds, function () use ($string) {

            switch ($string) {
                case 'attributes':
                    return $this->retrieveAttributes();
                    break;

                case 'categories':
                    return $this->retrieveCategories();
                    break;

                case 'currencies':
                    return $this->retrieveCurrencies();
                    break;

                case 'specifications':
                    return $this->retrieveSpecifications();
                    break;

                case 'vendors':
                    return $this->retrieveVendors();
                    break;

                default:
                    # code...
                    break;
            }

        })->toArray();

        // or return null
        return $data ?? null;
    }

    private function retrieveAttributes() {
        try {
            $params = [
                'with' => 'values',
            ];

            // get the categories from API
            return MPEAttributes::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.name') . "* StoredDataService.php: _Error retrieving attributes from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveCategories() {
        try {
            $params = [
                'with' => 'images,active_children_categories.images',
                'is_null' => 'top_id',
                'is_active' => true,
            ];

            // get the categories from API
            return MPECategories::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.name') . "* StoredDataService.php: _Error retrieving categories from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveCurrencies() {
        try {
            $params = [
                'is_enabled' => true,
            ];

            // get the categories from API
            return MPECurrencies::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.name') . "* StoredDataService.php: _Error retrieving currencies from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveSpecifications() {
        try {
            $params = [
                'with' => 'values',
            ];

            // get the categories from API
            return MPESpecifications::list($params);

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.name') . "* StoredDataService.php: _Error retrieving specifications from API_");
            Log::error($e);

            return false;
        }
    }

    private function retrieveVendors() {
        try {
            // get the categories from API
            return MPEVendors::list();

        } catch (Exception $e) {
            if(env('SLACK_ALERT_WEBHOOK')) SlackAlert::message('*' . config('app.name') . "* StoredDataService.php: _Error retrieving vendors from API_");
            Log::error($e);

            return false;
        }
    }
}
