<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CheckoutService extends Service
{
    /**
     * Create Stripe Payment Intent.
     */
    public function getPaymentIntent(array $data)
    {
        $response = $this->http()->post($this->getPath().'/settings/gateway/stripe/customers/payment-intent', $data);

        // call failed
        if ($response->failed()) {
            throw new \Exception('Error retrieving Stripe keys', 422);
        }

        return $response->json()['data'] ? collect($response->json()['data']) : null;
    }

    /**
     * Process payment.
     */
    public function processPayment($paymentMethodId = null, $billingAddress = null, bool $save = false)
    {
        $response = $this->http()->patch($this->getPath().'/checkout/payment', [
            'paymentMethodId' => $paymentMethodId,
            'billing_address' => $billingAddress,
            'customer_saved_info' => $save,
        ]);

        // error
        if ($response->failed()) {
            throw new \Exception('Unable to create order: '.$response->body(), 422);
        }
        if (isset($response['error']) && $response['error'] == true) {
            throw new \Exception($response['message'], $response['code']);
        }

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
    public function details(array $address, bool $save = false, ?string $with = null, ?array $vatRelief = null)
    {
        // save step 1
        $response = $this->http()->patch($this->getPath().'/checkout/details/', [
            'shipping_address' => $address,
            'customer_saved_info' => $save,
            'with' => $with,
            'tax_relief' => $vatRelief,
        ]);

        // api call failed
        if ($response->failed()) {
            throw new \Exception('Error attempting to update user details in checkout ', 422);
        }

        // any other errors
        if ($response['error']) {
            throw new \Exception($response['message'], $response['code']);
        }

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function setCartGroupShippingService($groupId, $serviceId, ?string $with = null)
    {
        // call to api
        $response = $this->http()->patch($this->getPath().'/checkout/shipping', [
            'cart_group_id' => $groupId,
            'shipping_service_id' => $serviceId,
            'with' => $with,
        ]);

        // api call failed
        // if ($response->failed()) throw new Exception('Error attempting to update checkout', 422);

        // any other errors
        if ($response['error']) {
            throw new \Exception($response['message'], $response['code']);
        }

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Save charity.
     *
     * @param int $charity_id
     */
    public function donation($charity_id, string $with = 'null')
    {
        $response = $this->http()->patch($this->getPath().'/checkout/donation', [
            'charity_id' => $charity_id,
            'with' => $with,
        ]);

        // api call failed
        if ($response->failed()) {
            throw new \Exception('Error attempting to update checkout', 422);
        }

        // any other errors
        if ($response['error']) {
            throw new \Exception($response['message'], $response['code']);
        }

        // if successful, return cart as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
