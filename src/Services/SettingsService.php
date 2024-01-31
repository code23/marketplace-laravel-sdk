<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class SettingsService extends Service
{
    public function getCommissionGroups($with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/settings/commission-groups', ['with' => $with]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the commission groups.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return data as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
