<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Http\Request;

class AddressLookupService extends Service
{
    /**
     * Retrieve address by postcode
     *
     * @param String $postcode
     *
     */
    public function findByPostcode(String $postcode)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/postcode/lookup', [
            'postcode' => $postcode
        ]);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return address as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve model by proximity to postcode
     *
     * @param String $postcode
     * @param String $model
     * @param Int $radius
     * @param Int $limit
     * @param String $with
     *
     */
    public function getNearbyModel(String $postcode, String $model, Int $radius = 10, Int $limit = 10, String $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/postcode/nearby', [
            'postcode' => $postcode,
            'model' => $model,
            'radius' => $radius,
            'limit' => $limit,
            'with' => $with,
        ]);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return address as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
