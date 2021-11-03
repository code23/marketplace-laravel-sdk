<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class AddressService extends Service
{
    public function create(Array $data)
    {
        // call
        $response = $this->http()->post($this->getPath() . '/address', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to create a new address!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function delete(Int $id)
    {
        // call
        $response = $this->http()->delete($this->getPath() . '/address/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to delete address', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function makeDefault(Int $id)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/address/' . $id . '/make-default');

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to change default address', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function update(Int $id, array $data)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/address/' . $id, $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to update address!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    // /**
    //  * get the most recently added products
    //  */
    // public function latest($count = 3)
    // {
    //     // call to api
    //     $response = $this->http()->get($this->getPath() . '/products', [
    //         'sort' => 'created_at',
    //         'status' => 'published',
    //         'limit' => $count,
    //         'with' => 'images,vendor',
    //     ]);

    //     // api call failed
    //     if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the latest products.', 422);

    //     // any other error
    //     if ($response['error']) throw new Exception($response['message'], $response['code']);

    //     // if successful, return collection or products or empty collection
    //     return $response->json()['data'] ? collect($response->json()['data']) : collect();
    // }
}
