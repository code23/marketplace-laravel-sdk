<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class AttributeService extends Service
{
    /**
     * Get the active product attributes
     *
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function list(
        $params = [
            'with' => 'values',
            'is_active' => 1,
        ],
        $oauth = null
    ) {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/attributes', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the attributes.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
