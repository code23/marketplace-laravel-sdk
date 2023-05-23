<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class AttributeService extends Service
{
    /**
     * Get the active product attributes
     *
     * @param array $params - See postman for available parameters
     */
    public function list($params = [
        'with' => 'values',
        'is_active' => 1,
    ])
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/attributes', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the attributes.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
