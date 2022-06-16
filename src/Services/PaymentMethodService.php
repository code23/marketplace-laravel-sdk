<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class PaymentMethodService extends Service
{

    /**
     * Get Stripe keys
     */
    public function getStripeApiKey()
    {
        $response = $this->http()->get($this->getPath() . '/settings/gateway/stripe/keys');

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
     * Retrieves available payment methods from API/Stripe
     */
    public function retrieve(array $data)
    {
        try {
            $response = $this->http()->post($this->getPath() . '/settings/gateway/stripe/retrievePaymentMethods', $data);

            if ($response->failed()) throw new Exception('Error during call to retrive payment methods!', 422);

            if ($response['error']) throw new Exception($response['message'], $response['code']);

            return $response->json()['data'] ? collect($response->json()['data']) : null;
        } catch(\Exception $e) {
            return null;
        }

    }

    /**
     * Delete card from customer
     */
    public function delete(array $data)
    {
        // call
        $response = $this->http()->delete($this->getPath() . '/settings/gateway/stripe/cards/remove', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to delete payment method', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Set Default card
     */
    public function setDefault(array $data)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/settings/gateway/stripe/setDefaultPaymentMethod', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to change default payment method', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Update card details
     */
    public function update(array $data)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/settings/gateway/stripe/cards/update', $data);

        // api call failed
        if ($response->failed()) \Log::info($response->body()); //throw new Exception('Error during call to update payment method!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
