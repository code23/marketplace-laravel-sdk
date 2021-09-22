<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ProductService extends Service
{
    public function list()
    {
        // call
        $response = $this->http()->get($this->getPath() . '/products');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // return product list
        return $response;
    }

    /**
     * get the most recently added products
     */
    public function latest($count = 3)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/tenant/products', [
            'sort' => 'created_at',
            'is_active' => true,
            'limit' => $count,
            'with' => 'images,vendor',
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the categories.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : ['message' => $response['message']];
    }
}
