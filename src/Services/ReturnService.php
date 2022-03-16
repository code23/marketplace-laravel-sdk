<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ReturnService extends Service
{
    /**
     * Return a list of Returns.
     *
     * By default returns all Orders on the site.
     *
     * @param Int $profile_id
     *      (optional) The user profile ID to get returns by.
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
        $response = $this->http()->get($this->getPath() . '/returns', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the returns!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return orders list
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Create a Return
     *
     * @param Int $order_id
     *      The order ID to create the Return for.
     * @param Array $products
     *      The products to be included in the Return.
     */
    public function create(Int $order_id, Array $products)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/returns', [$order_id, $products]);

        // call failed
        if ($response->failed()) throw new Exception('Error during call to create a new return!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

}
