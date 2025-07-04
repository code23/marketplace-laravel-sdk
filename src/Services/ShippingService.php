<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ShippingService extends Service
{

    /**
     * get shipping services
     *
     * @param array $vendorIds - optional array of vendor ids to filter by
     * @return void
     */
    public function get($vendorIds = [])
    {
        // set up params for the call
        $params = ['is_enabled' => true];

        // scope by vendor ids if provided
        if (!empty($vendorIds)) $params['in'] = 'vendor_id,' . implode(',', $vendorIds);

        // add to cart
        $response = $this->http()->get($this->getPath() . '/shipping/services', $params);

        // api call failed
        // if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * get shipping services
     *
     * @param array $params - optional array of API parameters
     * @return collection
     */
    public function getZones($params)
    {
        // get shipping zones
        $response = $this->http()->get($this->getPath() . '/shipping/zones', $params);

        // any errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return shipping zones as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
