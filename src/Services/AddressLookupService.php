<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Http\Request;

class AddressLookupService extends Service
{
    /**
     * Retrieve address by postcode
     *
     * @param String $postcode
     *
     */
    public function findByPostcode(String $postcode)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/postcode/lookup', [
            'postcode' => $postcode
        ]);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return address as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();

    }

}
