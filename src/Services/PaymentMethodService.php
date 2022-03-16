<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class PaymentMethodService extends Service
{
    public function create(Array $data)
    {
        // TODO : Check for final API route
        // api call
        $response = $this->http()->post($this->getPath() . '/payment-method', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error during call to create a new payment method!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function delete(Int $id)
    {
        // call
        $response = $this->http()->delete($this->getPath() . '/payment-method/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to delete payment method', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function setDefault(Int $id)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/payment-method/' . $id . '/make-default');

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to change default payment method', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    public function update(Int $id, array $data)
    {
        // call
        $response = $this->http()->patch($this->getPath() . '/payment-method/' . $id, $data);

        // api call failed
        if ($response->failed()) throw new Exception('Error during call to update payment method!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
