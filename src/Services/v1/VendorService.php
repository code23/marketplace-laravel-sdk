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

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the vendors retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the vendor
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }
}
