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
     * Get a nested list of categories and subcategories
     *
     * @param array $params - See postman for available parameters
     *                      - Use 'only_with_products' => true/false to filter categories with products only
     * @param $oauth - oauth token for when calling from artisan command
     */
    public function listNested($params = [], $oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/categories/nested', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Flatten given categories
     */
    public function flattenCategories($categories, $include = ['id', 'name', 'slug', 'parent_id', 'top_id'])
    {
        $result = [];

        foreach ($categories as $category) {
            $hold_cat = $category;
            foreach ($hold_cat as $key => $value) {
                // only include the keys we want
                if(!in_array($key, $include)) unset($hold_cat[$key]);
            }

            // add the category to the result array
            $result[] = $hold_cat;

            if (isset($category['children']) && !empty($category['children'])) {
                $children = $this->flattenCategories($category['children'], $include);
                $result = array_merge($result, $children);
            }
        }

        return $result;
    }
}
