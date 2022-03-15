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
     * Reset the session currencies data with a fresh collection from API
     */
    public function reset()
    {
        // get currencies fresh from API
        $currencies = $this->list();

        // write currencies data to session with default as active
        return $this->updateSession($currencies);
    }

    /**
     * Sets the active currency in user's session
     *
     * @param string $code A currency code
     */
    public function setActive(String $code)
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
     */
    public function updateSession($currencies, String $code = null)
    {
        // write updated currencies collection to session
        return session(['currencies' => $currencies->map(function ($currency) use ($code) {
            $data = [
                'code' => $currency['code'],
                'symbol' => $currency['symbol'] ?? $currency['code'],
                'is_default' => $currency['is_default'],
                'is_active' => $currency['is_default'],
            ];

            // if a code was provided
            if($code) {
                // if the code matches the current item set as active
                $data['is_active'] = $currency['code'] === $code ?? false;
            }

            // save item
            return $data;
        })]);
    }
}
