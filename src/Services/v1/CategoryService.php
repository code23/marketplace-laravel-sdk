<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPEStored;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CategoryService extends Service
{
    /**
     * Generate a breadcrumb trail for a given category id
     */
    public function breadcrumb($id)
    {
        // get all categories from storage, or quit
        if (!$categories = MPEStored::retrieve('categories')) return false;

        // flatten the categories
        $flat_categories = $this->flattenCategories($categories);

        // if category not found in session data, return false
        if (!$category = $this->findInArrayByID($id, $flat_categories)) return false;

        // create breadcrumb array (in reverse order)
        $breadcrumb = [$category];

        // if the category has a parent, recurse
        $parents = $this->parentWalker($category, $flat_categories);

        // merge the parents into the breadcrumb
        if (!empty($parents)) $breadcrumb = array_merge($breadcrumb, $parents);

        // return the reversed breadcrumb
        return array_reverse($breadcrumb);
    }

    /**
     * Flatten given categories
     */
    public function flattenCategories($categories, $include = ['id', 'name', 'slug', 'parent_id', 'top_id', 'is_active'])
    {
        $result = [];

        foreach ($categories as $category) {
            $hold_cat = $category;
            foreach ($hold_cat as $key => $value) {
                // only include the keys we want
                if (!in_array($key, $include)) unset($hold_cat[$key]);
            }

            // add the category to the result array
            $result[] = $hold_cat;

            if (isset($category['active_children']) && !empty($category['active_children'])) {
                $children = $this->flattenCategories($category['active_children']);
                $result = array_merge($result, $children);
            }
        }

        return $result;
    }

    /**
     * Find a category in session by id
     */
    public function findInArrayByID($id, $categories = null)
    {
        // if no categories passed in and none in session, return false
        if (!$categories && !$session_categories = session('categories')['data']) return false;

        // if no categories passed in, but some in session, flatten them
        if (!$categories) $categories = $this->flattenCategories($session_categories);

        // search the categories for id
        return collect($categories)->firstWhere('id', $id);
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
        'is_active' => true,
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
     * parent walker
     */
    public function parentWalker($category, $categories, &$parents = [])
    {
        // if the category has a parent
        if (isset($category['parent_id']) && $category['parent_id']) {
            // if parent category exists
            if ($parent_category = $this->findInArrayByID($category['parent_id'], $categories)) {
                // add it to the array
                $parents[] = $parent_category;

                // if the parent has a parent, recurse
                $this->parentWalker($parent_category, $categories, $parents);
            }
        }

        return $parents;
    }

    /**
     * Get a nested list of categories and subcategories with products
     */
    public function categoriesWithProducts($params = [], $oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/categories/menu', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
