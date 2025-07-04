<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CategoryService extends Service
{
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

    /**
     * Retrieves a category by its slug.
     *
     * @param String $slug The slug of the category.
     * @param Array $params API parameters.
     * @throws Exception If the given category was not found, if there are errors, or if the API call fails.
     * @return Collection The category as a collection.
     */
    public function getBySlug(String $slug, Array $params = [])
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/categories/slug/' . $slug, $params);

        // category not found
        if ($response->status() == 404) throw new Exception('The given category was not found', 404);

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the products by category.', 422);

        // return category as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

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

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

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

        // errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
