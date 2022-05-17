<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CartService extends Service
{
    /**
     * Retrieve cart for authenticated user
     *
     */
    public function get()
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/cart');

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the cart.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }
    
    /**
     * Retrieve cart by Id
     *
     * @param Int $id
     */
    public function getById(Int $id)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/cart/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the cart.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }

}
