<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CharityService extends Service
{
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
}
