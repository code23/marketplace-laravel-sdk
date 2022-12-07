<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class SpecificationService extends Service
{

    /**
     * Get a specification by id
     *
     * @param integer $id specification id to get
     * @param string $with optionally include comma separated relationships
     */
    public function get(String $code, String $with = 'values')
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/specifications/code/' . $code, ['with' => $with]);

        // specification not found
        if ($response->status() == 404) throw new Exception('The given specification was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the products by specification.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return products as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}