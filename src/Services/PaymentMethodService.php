<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class PaymentMethodService extends Service
{
    /**
     * Add a new card
     */
    public function add($payload)
    {
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/customers/payment-methods/add', [
            'payment_method' => $payload['payment_method'], // Payment method provided by Stripe
        ]);

        // error
        if ($response->failed()) throw new Exception('Unable to create Stripe customer', 422);
        //if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get Stripe keys
     */
    public function getStripeApiKey()
    {
        $response = $this->http()->get($this->getPath() . '/settings/gateway/stripe/keys');

        // call failed
        if ($response->failed()) throw new Exception('Error retrieving Stripe keys', 422);

        return $response->json()['data'] ? $response->json()['data']['publishable_key'] : null;
    }

    /**
     * Setup Stripe Payment Intent
     */
    public function getPaymentIntent(array $data)
    {
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/setupIntent', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error retrieving Stripe keys', 422);

        return $response->json()['data'] ? collect($response->json()['data']) : null;
    }

    /**
     * Setup Stripe Intent & set card on Stripe customer
     */
    public function getSetupIntent(array $data)
    {
        $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/setupIntent', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error retrieving Stripe keys', 422);

        return $response->json()['data'] ? collect($response->json()['data']) : null;
    }


    /**
     * Retrieves a user's available payment methods from API/Stripe
     *
     * @return void
     */
    public function retrieve()
    {
        $response = $this->http()->get($this->getPath() . '/settings/gateway/stripe/customers/payment-methods/list');

        // if ($response->failed()) throw new Exception('Error during call to retrive payment methods!', 422);

        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response->json()['data'] ? collect($response->json()['data']) : null;
    }

    /**
     * Delete a payment method
     *
     * @param array $payment_method - payment method id - e.g. pm_xxxxx
     * @return void
     */
    public function delete(array $payment_method)
    {
        // call
        $response = $this->http()->delete($this->getPath() . '/settings/gateway/stripe/customers/payment-methods/delete', $payment_method);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to delete payment method', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response->json()['data'] ? collect($response->json()['data']) : null;
    }
}
