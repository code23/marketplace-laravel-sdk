<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CharityService extends Service
{
    /**
     * Retrieve all charities
     *
     * @return Collection of charities
     */
    public function list(
        $params = [],
        $oauth = null,
    ) {
        // retrieve charities
        $response = $this->http($oauth)->get($this->getPath() . '/charities', $params);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the charities retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the charity
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Save a vendor
     */
    public function save(array $data)
    {
        // send data
        $response = $this->http()->post($this->getPath() . '/charities/register', $data);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user & charity.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return true;
    }

    /**
     * Check charity name is unique
     *
     * @param String $name
     *      The name to check for uniqueness within the tenant
     *
     * @return boolean
     */
    public function nameIsUnique($name)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/charities/is-name-unique', [
            'name' => $name,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to check charity name is unique.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // true/false
        return $response;
    }

    /**
     * Retrieve a charity by slug
     *
     * @param String $slug
     *      Charity slug to retrieve.
     *
     * @param String $with
     *      Charity relationships (comma separated) to include
     */
    public function getBySlug(String $slug, $params = [])
    {
        // retrieve charity
        $response = $this->http()->get($this->getPath() . '/charities/slug/' . $slug, $params);

        // charity not found
        if ($response->status() == 404) return $response;

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the charity retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the charity
        return $response['data'];
    }
}
