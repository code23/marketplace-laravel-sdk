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
        // \Log::info($payload);
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/setDefaultPaymentMethod', [
            'payment_method' => $payload['payment_method'], // Payment method provided by Stripe
            'cart' => $payload['cart'] ?? null, // Cart ID
            'model' => 'App\\Models\\Api\\v1\\Tenant\\Users\\Profile', // Pass the model used
            'id' => $payload['user'], // Pass the UUID or ID of the user with a Stripe account
        ]);

        if ($response->failed()) throw new Exception('Unable to create Stripe customer', 422);

        //Process Payment
        if(!$response->failed() && $payload['process_payment'] === true) {
            $this->processPayment($payload['cart'], 'App\\Models\\Api\\v1\\Tenant\\Users\\Profile');
        }

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

        if ($response->failed()) {
            throw new Exception('Unable to create order: ' . $response->body(), 422);
        }

        // any other error
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        return $response;
    }

    /*
     * Save Step 1 (details) data
     *
     * @param Array $address
     * @param boolean $save - save the shipping address to the user profile?
     * @return Collection updated cart array
     */
    public function details(Array $address, Bool $save = false)
    {
        // add to cart
        $response = $this->http()->patch($this->getPath() . '/checkout/details/', [
            'shipping_address' => $address,
            'customer_saved_info' => $save,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function setCartGroupShippingService($groupId, $serviceId)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/checkout/shipping', [
            'cart_group_id' => $groupId,
            'shipping_service_id' => $serviceId,
        ]);

        // api call failed
        // if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
