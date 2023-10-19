<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CheckoutService extends Service
{
    /**
     * Process payment
     */
    public function processPayment($params = [])
    {
        $response = $this->http()->patch($this->getPath() . '/checkout/payment', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to create order: ' . $response->body(), 422);

        // any other errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
