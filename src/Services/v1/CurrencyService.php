<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECache;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CurrencyService extends Service
{
    /**
     * Returns the active currency
     */
    public function active()
    {
        $currencies = MPECache::get('currencies');
        return (!empty($currencies) && session('active_currency_code'))
            ? $currencies->firstWhere('code', session('active_currency_code'))
            : null;
    }

    /**
     * Retrieve available currencies from API
     */
    public function list($params = [
        'is_enabled' => true,
    ], $oauth = null)
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

    /**
     * Sets the active currency in user's session
     *
     * @param string $code A currency code
     *
     * @return boolean
     */
    public function setActiveByCode(String $code)
    {
        // get the currencies from the stored data
        $currencies = MPECache::get('currencies');

        // if code not found in available currencies
        if(!$currencies->firstWhere('code', $code)) return false;

        // update active currency in session
        session(['active_currency_code' => $code]);

        return true;
    }
}
