<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CurrencyService extends Service
{
    /**
     * Returns the active currency from the session.
     */
    public function active()
    {
        return (session('currencies') && session('active_currency_code'))
            ? session('currencies')->firstWhere('code', session('active_currency_code'))
            : null;
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
        if ($response->failed()) throw new Exception('Error retrieving the currencies.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieves available currencies from API & stores them in session.
     */
    public function init()
    {
        // if no currencies in session
        if(!session('currencies')) {

            // retrieve and write currencies collection to session
            session(['currencies' => $this->list()->map(function ($currency) {
                return [
                    'id'         => $currency['id'],
                    'code'       => $currency['code'],
                    'symbol'     => $currency['symbol'],
                    'label'      => $currency['label'],
                    'is_default' => $currency['is_default'],
                ];
            })]);

        }

        // if active_currency_code not in session
        if(!session('active_currency_code')) {
            // set to default currency for site
            session(['active_currency_code' => session('currencies')->firstWhere('is_default', true)['code']]);
        }
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
        // get the currencies from the user session if available, or API
        $currencies = session('currencies') ?? $this->list();

        // if not found
        if(!$currencies) return false;

        // if code not found in available currencies
        if(!$currencies->firstWhere('code', $code)) return false;

        // update active currency in session
        session(['active_currency_code' => $code]);

        return true;
    }
}
