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
     * Get a list of categories with their images
     *
     * @param integer $level How many levels deep to include - 0 = top level
     */
    public function list($level = null)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/tenant/categories', [
            'with' => 'images',
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the categories.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        if($response->json()['data']) {
            return collect($response->json()['data'])
                    ->where('is_active')
                    // when level 0 specified, only include top level categories
                    ->when($level === 0, function($q) {
                        return $q->where('parent_id', null);
                    });
        }

        // else return error
        return ['message' => $response['message']];
    }

    public function productsByCategory($id)
    {
        // send request
        $response = $this->http()->get($this->getPath() . '/tenant/categories/' . $id, [
            'with' => 'products.images,products.vendor',
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to retrieve the categories.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return categories as collection
        if ($response->json()['data']) {
            return collect($response->json()['data']['products'])
                ->where('is_active');
        }

        // else return error
        return ['message' => $response['message']];
    }
}
