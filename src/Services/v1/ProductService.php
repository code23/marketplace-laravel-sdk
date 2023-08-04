<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECategories;
use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class ProductService extends Service
{
    /**
     * Add to recently viewed products
     *
     * @param int $id - product ID to add to array in session
     *
     */
    public function addToRecentlyViewed($id)
    {
        // if no recently viewed products session exists, create it
        if (!session('recently_viewed_products')) session()->put('recently_viewed_products');

        // add id to collection, remove duplicates, convert to array, and update session
        session([
            'recently_viewed_products' => collect(session('recently_viewed_products'))
                ->prepend($id)
                ->unique()
                ->toArray()
        ]);
    }

    /**
     * Get a list of all products across all vendors
     *
     * @param Array $params - query parameters
     *
     * @return Collection
     */
    public function list(
        Array $params = [
            'status' => 'published',
        ],
    ) {
        // call
        $response = $this->http()->get($this->getPath() . '/products', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        // return $response->json()['data'] ? collect($response->json()['data']) : collect();
        if (in_array('paginate', $params)) return $response->json() ? collect($response->json()) : collect();
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
