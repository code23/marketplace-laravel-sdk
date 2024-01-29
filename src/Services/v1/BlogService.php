<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class BlogService extends Service
{
    /**
     * Get a list of blog categories
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function categories($params = [
        'is_active' => true
    ], $oauth)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/blog/categories', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the blog categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return blog categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
