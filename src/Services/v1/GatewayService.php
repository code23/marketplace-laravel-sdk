<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class GatewayService extends Service
{
    /**
     * Get PayPal gateway settings
     *
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function paypalSettings($oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/settings/gateway/paypal/status');

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the paypal gateway settings.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get mapped PayPal gateway data for laravel paypal package: https://srmklive.github.io/laravel-paypal/docs.html
     */
    public function laravelPaypalConfig()
    {
        $response = $this->paypalSettings();

        // map api response to config array;
        return [
            'mode' => $response['test_mode'] ? 'sandbox' : 'live',
            'live' => [
                'client_id'         => $response['live_client_id'],
                'client_secret'     => $response['live_client_secret'],
            ],
            'sandbox' => [
                'client_id'         => $response['sandbox_client_id'],
                'client_secret'     => $response['sandbox_client_secret'],
            ],
            'payment_action' => 'Sale',
            'currency'       => session()->get('active_currency_code'),
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
    }
}
