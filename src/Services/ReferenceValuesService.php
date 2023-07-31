<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ReferenceValuesService extends Service
{
    /**
     * Lookup a reference value by category
     * @param string $category The category to lookup
     * @param bool $filterByMarketType Filter by market type
     */
    public function byCategory($category, $filterByMarketType = false,)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/reference-values/lookup', [
            'category' => $category,
            'filter_by_market_type' => $filterByMarketType,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the reference value lookup.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of items or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
