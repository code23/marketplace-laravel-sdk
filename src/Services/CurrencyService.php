<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CurrencyService extends Service
{
    /**
     * Returns the active currency from the session. Requires MPESessionCurrencies included in kernel.
     */
    public function active()
    {
        return session('currencies') ? session('currencies')->firstWhere('is_active', true) : null;
    }

    /**
     * Retrieve available currencies from API
     *
     * @param Boolean $hideDisabled Whether to include disabled currencies or not. Default is to hide them.
     */
    public function list($hideDisabled = true)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/settings/currencies', [
            'is_enabled' => $hideDisabled,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst retrieving the currencies.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Rewrites the session currencies and determines the active item.
     */
    public function reset()
    {
        // if a user is logged in get their profile's preferred currency
        $userCurrencyCode = request()->user() ? request()->user()->profile['currency']['code'] : null;

        // get the currencies from the user session or API if not in session
        $currencies = session('currencies') ?? $this->list();

        // write currencies data to session
        return $this->updateSession($currencies, $userCurrencyCode);
    }

    /**
     * Sets the active currency in user's session
     *
     * @param string $code A currency code
     */
    public function setActiveByCode(String $code)
    {
        // get the currencies from the user session
        $currencies = session('currencies');

        // if not found
        if(!$currencies) return false;

        // if code not found in available currencies
        if(!$currencies->firstWhere('code', $code)) return false;

        // write updated currencies data to session with new active code
        return $this->updateSession($currencies, $code);
    }

    /**
     * Writes currency data to the user's session
     *
     * @param $currencies currencies to write to session
     *
     * @param string $code Currency code to set as active
     */
    public function updateSession($currencies, String $code = null)
    {
        // write updated currencies collection to session
        return session(['currencies' => $currencies->map(function ($currency) use ($code) {
            return [
                'id'         => $currency['id'],
                'code'       => $currency['code'],
                'symbol'     => $currency['symbol'],
                'label'      => $currency['label'],
                'is_default' => $currency['is_default'],
                // if code given set true/false based on it matching this currency, otherwise use the is_default
                'is_active'  => $code ? $currency['code'] === $code : $currency['is_default'],
            ];
        })]);
    }
}
