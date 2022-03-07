<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CurrencyService extends Service
{
    public function list()
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/settings/currencies');

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst retrieving the currencies.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
