<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ProductService extends Service
{
    /**
     * Get a list of all products
     *
     * @param String $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor'
     *
     * @return Collection
     */
    public function list($with = null)
    {
        // call
        $response = $this->http()->get($this->getPath() . '/products', [
            'with' => $with,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get the most recently added products.
     *
     * @param Int $count
     *      (optional) The number of reviews to retrieve (default = 3).
     *
     * @return Collection
     */
    public function latest(Int $count = 3)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/products', [
            'sort' => 'created_at',
            'status' => 'published',
            'limit' => $count,
            'with' => 'images,vendor',
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the latest products.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
