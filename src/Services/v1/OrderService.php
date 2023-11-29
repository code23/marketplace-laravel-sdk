<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class OrderService extends Service
{
    /**
     * Return a list of authenticated user's Orders.
     *
     * @return Collection
     */
    public function list($params = [
        'with' => 'product.images,currency',
        'sort' => 'created_at,desc',
        'paginate' => 10,
    ])
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/orders/customer', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the orders!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return orders list
        return $response->json() ? collect($response->json()) : collect();
    }
}
