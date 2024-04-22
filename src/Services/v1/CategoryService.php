<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CategoryService extends Service
{
    /**
     * Get a nested list of categories and subcategories
     *
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function list($params = [
        'with' => 'images,active_children_categories.images',
        'is_null' => 'top_id',
    ], $oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/categories', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a nested list of populated categories and subcategories
     *
     * @param array $params - See postman for available parameters
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function populatedList($params = [
        'with' => 'images',
    ], $oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/categories/populated', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
