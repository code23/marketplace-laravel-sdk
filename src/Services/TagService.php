<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class TagService extends Service
{
    /**
     * Get a list of active tags
     *
     * @param array $params - See postman for available parameters
     */
    public function list($with = null)
    {
        $params = ['is_active' => true];
        if($with) $params['with'] = $with;

        // send request
        $response = $this->http()->get($this->getPath() . '/tags', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the tags.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return tags as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
