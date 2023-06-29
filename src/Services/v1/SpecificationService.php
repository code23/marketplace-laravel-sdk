<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class SpecificationService extends Service
{
    /**
     * Retrieve available specifications from API
     */
    public function list($params = [
        'with' => 'values',
    ], $oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/specifications', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error retrieving the specifications.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
