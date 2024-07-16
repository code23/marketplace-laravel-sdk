<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class VendorService extends Service
{
    /**
     * Retrieve all vendors
     *
     * @return Collection of vendors
     */
    public function list(
        $params = [],
        $oauth = null,
    ) {
        // retrieve vendors
        $response = $this->http($oauth)->get($this->getPath() . '/vendors', $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendors retrieval.', 422);

        // return the vendor
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Save a vendor
     */
    public function save(array $data)
    {
        // send data
        $response = $this->http()->post($this->getPath() . '/vendors/register', $data);

        // error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user & vendor.', 422);

        return true;
    }
}
