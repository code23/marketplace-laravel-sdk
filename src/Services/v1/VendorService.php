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

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user & vendor.', 422);

        return true;
    }

    /**
     * Retrieve application
     */
    public function retrieveApplication(array $data)
    {
        // send data
        $response = $this->http()->post($this->getPath() . '/vendors/retrieve-application', $data);

        // error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to resume application.', 422);

        // return the application data
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Update application
     */
    public function update(array $data)
    {
        // send data
        $response = $this->http()->patch($this->getPath() . '/vendors/resume-application', $data);

        // error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to update the application.', 422);

        return true;
    }
}