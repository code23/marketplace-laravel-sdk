<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Illuminate\Support\Facades\Auth;
use Exception;

class OrderService extends Service
{
    /**
     * Download an invoice relating to an order
     * 
     * @param int $id   invoice id
     * 
     * @return
     */
    public function downloadInvoiceById(int $id)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/invoices/' . $id . '/download');

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the invoice requested!', 422);

        // return orders list
        return $response->body();
    }


    /**
     * Return a list of authenticated user's Orders.
     *
     * @return Collection
     */
    public function list($with = 'product.images,currency')
    {
        // create params - include products & images
        $params = [
            'with' => $with,
            'sort' => 'created_at,desc',
        ];

        // call to api
        $response = $this->http()->get($this->getPath() . '/orders/customer', $params);

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
    public function getByNumberByCustomer(String $number, String $with = 'currency,transaction,order_groups.vendor,shipping_address,billing_address,invoice.files')
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

    /**
     * Get invoice by ID
     *
     * @param Int $id Invoice ID
     */
    public function getInvoiceByID(Int $id)
    {
        // TODO : Check for final API route
        // call api
        $response = $this->http(null, 'application/pdf')->get($this->getPath() . '/invoices/' . $id . '/download');

        if ($response->failed()) throw new Exception('Unable to download the invoice!', 422);

        // return order        
        return $response->body();
    }
}
