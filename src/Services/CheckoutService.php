<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CheckoutService extends Service
{
    /**
     * Add a new card and set it as default payment method
     */
    public function addNewDefaultCard($payload)
    {
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/setDefaultPaymentMethod', [
            'payment_method' => $payload['payment_method'], // Payment method provided by Stripe
        ]);

        // error
        // if ($response->failed()) throw new Exception('Unable to create Stripe customer', 422);
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Process payment
     */
    public function processPayment($paymentMethodId = null, $billingAddress = null)
    {
        $response = $this->http()->patch($this->getPath() . '/checkout/payment', [
            'paymentMethodId' => $paymentMethodId,
            'billing_address' => $billingAddress,
        ]);

        // error
        if ($response->failed()) throw new Exception('Unable to create order: ' . $response->body(), 422);
        // if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /*
     * Save Step 1 (details) data
     *
     * @param Array $address
     * @param boolean $save - save the shipping address to the user profile?
     * @return Collection updated cart array
     */
    public function details(Array $address, Bool $save = false, String $with = null)
    {
        // save step 1
        $response = $this->http()->patch($this->getPath() . '/checkout/details/', [
            'shipping_address'    => $address,
            'customer_saved_info' => $save,
            'with'                => $with,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function setCartGroupShippingService($groupId, $serviceId, String $with = null)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/checkout/shipping', [
            'cart_group_id'       => $groupId,
            'shipping_service_id' => $serviceId,
            'with'                => $with,
        ]);

        // api call failed
        // if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
