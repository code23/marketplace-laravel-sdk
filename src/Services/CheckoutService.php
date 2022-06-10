<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CheckoutService extends Service
{
    /**
     * Retrieve payment methods from Stripe and charge the customer
     */
    public function setupDefaultPayment($payload)
    {
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/setDefaultPaymentMethod', [
            'payment_method' => $payload['payment_method'], // Payment method provided by Stripe
            'cart' => $payload['cart'], // Cart ID
            'model' => 'App\\Models\\Api\\v1\\Tenant\\Users\\Profile', // Pass the model used
            //'id' => $payload['user']['id'], // Pass the UUID or ID of the user with a Stripe account
        ]);

        if ($response->failed()) throw new Exception('Unable to create Stripe customer', 422);

        // Process Payment
        $this->processPayment($payload['cart'], 'App\\Models\\Api\\v1\\Tenant\\Users\\Profile');

        return $response;

    }

    /**
     * Process payment
     */
    public function processPayment($cart = null, $model = null, $card = null)
    {
        $response = $this->http()->patch($this->getPath() . '/checkout/payment', [
            'cart' => $cart, // Cart ID
            'model' => $model, // Pass the model used
            'card' => $card
        ]);

        if ($response->failed()) throw new Exception('Unable to create order: ' . $response->body(), 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
