<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CheckoutService extends Service
{
    /**
     * Process payment
     */
    public function processPayment($paymentMethodId = null, $billingAddress = null, Bool $save = false)
    {
        $response = $this->http()->patch($this->getPath() . '/checkout/payment', [
            'paymentMethodId' => $paymentMethodId,
            'billing_address' => $billingAddress,
            'customer_saved_info' => $save,
        ]);

        // error
        if ($response->failed()) throw new Exception('Unable to create order: ' . $response->body(), 422);
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /*
     * Save Step 1 (details) data
     *
     * @param Array $address
     * @param boolean $save - save the shipping address to the user profile?
     * @param string $with - relationships to include
     * @param Array $vatRelief - if it applies, and the reason
     * @return Collection updated cart array
     */
    public function details(array $address, Bool $save = false, String $with = null, array $vatRelief = null)
    {
        // save step 1
        $response = $this->http()->patch($this->getPath() . '/checkout/details/', [
            'shipping_address'    => $address,
            'customer_saved_info' => $save,
            'with'                => $with,
            'tax_relief'          => $vatRelief,
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