<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Support\Str;

class CategoryService extends Service
{
    public function category($name)
    {
        // fake categories
        $subCategories = [
            [
                'name' => 'Weddings',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Birthdays',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Corporate Events',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Village Fetes',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Grand Opening',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Launch Day',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Reunion',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Space Walks',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
            [
                'name' => 'Easter Egg Hunts',
                'image' => 'https://picsum.photos/600/600?random=' . Str::random(1),
            ],
        ];

        // return them
        return $subCategories;
    }

    /**
     * Get a list of categories
     *
     * @param integer $level How many levels deep to include - 0 = top level
     * @param string $with optionally include comma separated relationships
     */
    public function list($level = null, $with = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/categories', ['with' => $with]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the categories.', 422);

        // any other errors
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        if($response->json()['data']) {
            return collect($response->json()['data'])
                ->where('is_active')
                // ->when('parent_id', function($q) {
                //     return $q->where()
                // })
                // when level 0 specified, only include top level categories
                ->when($level === 0, function($q) {
                    return $q->where('parent_id', null);
                });
        }

        // if no results
        return collect();
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
