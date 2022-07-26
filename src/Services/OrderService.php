<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Illuminate\Support\Facades\Auth;
use Exception;

class OrderService extends Service
{
    /**
     * Return a list of authenticated user's Orders.
     *
     * @return Collection
     */
    public function list($with = 'product.images, currency')
    {
        // create params - include products & images
        $params = [
            'with' => $with,
            'profile_id' => auth()->user()->profile['id'],
        ];

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
        // TODO : Check for final API route
        // call api
        $response = $this->http()->get($this->getPath() . '/orders/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the order!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return order
        return $response;
    }

    /**
     * Get a single Order by order number
     *
     * @param String $number
     *      The Order ID to show.
     */
    public function getByNumberByCustomer(String $number, String $with = 'currency,transaction,order_groups.vendor,shipping_address,billing_address')
    {
        // TODO : Check for final API route
        // call api
        $response = $this->http()->get($this->getPath() . '/orders/customer/number/' . $number, [
            'with' => $with
        ]);

        // api call failed
        // if ($response->failed()) throw new Exception('Unable to retrieve the order!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return order
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
