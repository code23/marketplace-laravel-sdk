<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class ReferenceValuesService extends Service
{
    /**
     * Lookup a reference value by category
     * @param array $params The parameters to pass to the API
     * @param array|null $oauth The OAuth token to use for the request
     */
    public function byCategory($params, $oauth = null)
    {
        // call to api
        $response = $this->http($oauth)->get($this->getPath() . '/reference-values/lookup', $params);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the reference value lookup.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of items or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
