<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class CategoryService extends Service
{
    /**
     * Get a nested list of categories and subcategories
     *
     * @param array $params - See postman for available parameters
     */
    public function list($params = [
        'with' => 'images,active_children_categories',
        'is_null' => 'top_id',
        'is_active' => true,
    ])
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/categories', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * @param string $with - relationships to include
     */
    public function listTopLevel($with = 'images')
    {
        return $this->list([
            'with' => $with,
            'is_null' => 'top_id',
            'is_active' => true,
        ]);
    }

    /**
     * @param string $with - relationships to include
     */
    public function listFlat($with = 'images')
    {
        return $this->list([
            'with' => $with,
            'is_active' => true,
        ]);
    }

    /**
     * Get a category by id
     *
     * @param integer $id category id to get
     * @param string $with optionally include comma separated relationships
     */
    public function get(Int $id, String $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/categories/' . $id, ['with' => $with]);

        // category not found
        if($response->status() == 404) throw new Exception('The given category was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the products by category.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return products as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    public function getBySlug(String $slug, String $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/categories/slug/' . $slug, ['with' => $with]);

        // category not found
        if($response->status() == 404) throw new Exception('The given category was not found', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the products by category.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return products as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
