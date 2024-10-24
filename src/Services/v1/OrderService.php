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

        // error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the orders!', 422);

        // return orders list
        return $response->json() ? collect($response->json()) : collect();
    }

    /**
     * Return a list of authenticated user's Booking Orders.
     *
     * @return Collection
     */
    public function bookingOrderslist($params = [
        'with' => 'currency',
        'sort' => 'created_at,desc',
        'paginate' => 10,
    ])
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/booking-calendar/bookings', $params);

        // error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the booking orders!', 422);

        // return orders list
        return $response->json() ? collect($response->json()) : collect();
    }

    /**
     * Get invoice by ID
     *
     * @param Int $id Invoice ID
     */
    public function getInvoiceByID(Int $id)
    {
        // call api
        $response = $this->http(null, 'application/pdf')->get($this->getPath() . '/invoices/' . $id . '/download');

        if ($response->failed()) throw new Exception('Unable to download the invoice!', 422);

        // return order
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
