<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class OrderService extends Service
{
    /**
     * Return a list of Orders.
     *
     * By default returns all Orders on the site.
     *
     * @param Int $profile_id
     *      (optional) The user profile ID to get orders by.
     *
     * @return Collection
     */
    public function list(Int $profile_id = null)
    {
        // create params - include products & images
        $params = ['with' => 'product.images'];

        // conditionally include provided IDs
        if($profile_id) $params['profile_id'] = $profile_id;

        // call to api
        $response = $this->http()->get($this->getPath() . '/orders', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the orders!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return orders list
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a single Order 
     *
     * @param Int $id
     *      The Order ID to show.
     */
    public function get(Int $id)
    {
        // call api
        $response = $this->http()->get($this->getPath() . '/orders/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the order!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return order 
        return $response;
    }
}
