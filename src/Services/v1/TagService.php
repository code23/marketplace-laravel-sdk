<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class TagService extends Service
{
    /**
     * Retrieve available tags from API
     */
    public function list(
        $params = [],
        $oauth = null
    ) {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/tags', $params);

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Error retrieving the tags.', 422);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
