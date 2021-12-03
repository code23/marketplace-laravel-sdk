<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ReferenceValuesService extends Service
{
    /**
     * Lookup a reference value by category
     */
    public function byCategory($category)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/reference-values/lookup', [
            'category' => $category,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the reference value lookup.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of items or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
