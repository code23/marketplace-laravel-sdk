<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Illuminate\Support\Facades\Log;
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
        array $params = [],
    ) {
        // call
        $response = $this->http()->get($this->getPath() . '/products', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if paginated, return collection of full response data or empty collection
        if (isset($params['paginate'])) return $response->json() ? collect($response->json()) : collect();

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of products with filters and pagination
     *
     * @param Array $params
     *
     * @return Collection
     */
    public function listWithFilters(
        array $params = []
    ) {
        // if paginate is set, rename it to mpe_paginate
        if (isset($params['paginate'])) {
            $params['mpe_paginate'] = $params['paginate'];
            unset($params['paginate']);
        }

        // call
        $response = $this->http()->post($this->getPath() . '/products/filter', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        // paginated
        if (isset($params['mpe_paginate']) && $params['mpe_paginate']) return $response->json() ? collect($response->json()) : collect();
        // non paginated
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a given product's related products.
     * @param array $product The product to find related products for
     * @param int $limit max number of results to return
     * @param array $params API parameters to use
     */
    public function related(
        array $product,
        int $limit = 10,
        array $params = [
            'with' => 'images,vendor',
        ],
    ) {
        // create empty products collection
        $products = collect();

        // check for up-sells
        if (isset($product['up_sells']) && count($product['up_sells'])) {
            $products = collect($product['up_sells']);
        }

        // if number of up-sells is greater than or equal to max return amount
        if ($products->count() >= $limit) {
            // return in random order limited to return count
            return $products->shuffle()->take($limit);
        }

        // if not enough up-sells…

        // get array of category ids from the product
        $categoryIDs = collect($product['categories'])->pluck('id')->toArray();

        // create exclusion list of this product's id and that of products already found
        $exclude = collect($product['id'])->merge($products->pluck('id'))->toArray();

        // create api call parameters
        $params['categories'] = $categoryIDs;
        $params['not_in'] = 'id,' . implode(',', $exclude);
        $params['sort'] = ['random'];
        $params['paginate'] = $limit - $products->count();

        // call api for the extra products required
        try {
            $shortfall = $this->listWithFilters($params);
        } catch (Exception $e) {
            Log::error($e);

            // if error, return the products found so far
            return $products;
        }

        // merge shortfall into products collection and return
        return $products->merge($shortfall['data']['products']['data']);
    }

    /**
     * Get a single product by product & vendor slug, with optional relationships
     *
     * @param string $vendorSlug Vendor Slug
     * @param string $productSlug Product Slug
     * @param array $params API parameters to use
     * 
     * @return Collection
     */
    public function get(string $vendorSlug, string $productSlug, array $params = [])
    {
        // call api
        $response = $this->http()->get($this->getPath() . '/vendor/' . $vendorSlug . '/product/' . $productSlug, $params);

        // not found
        if ($response->status() == 404) throw new Exception($response['message'], 404);

        // not published
        if (isset($response->json()['data']) && empty($response->json()['data']['products'])) throw new Exception('Product not published', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the product!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
