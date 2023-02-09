<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class SpecificationService extends Service
{
    /**
     * Get a specification by code
     *
     * @param string $code specification code to get
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

    /**
     * List specification items
     *
     * @param string $with optionally include comma separated relationships
     * @param string $in optionally include comma separated specification codes to scope to, e.g.
     */
    public function list(String $with = 'values', String $in = null)
    {
        // prepare payload data
        $payload = [
            'with' => $with,
        ];
        if($in) $payload['in'] = $in;

        // send request
        $response = $this->http()->get($this->getPath() . '/specifications', $payload);

        // specification not found
        if ($response->status() == 404) throw new Exception('No specifications found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the specifications.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return specifications as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
