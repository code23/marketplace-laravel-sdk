<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEStored;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CurrencyService extends Service
{
    /**
     * Returns the active currency
     */
    public function active()
    {
        return (MPEStored::retrieve('currencies') && session('active_currency_code'))
            ? collect(MPEStored::retrieve('currencies'))->firstWhere('code', session('active_currency_code'))
            : null;
    }

    /**
     * Retrieve available currencies from API
     */
    public function list($params = [
        'is_enabled' => true,
    ], $oauth)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/settings/currencies', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error retrieving the currencies.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
