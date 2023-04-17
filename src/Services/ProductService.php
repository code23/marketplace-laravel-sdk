<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Facades\MPECategories;
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
        if (!session('recently_viewed_products')) {
            session()->put('recently_viewed_products');
        }

        // add to recently viewed products collection within session and trim to length limit
        session([
            'recently_viewed_products' => collect(session('recently_viewed_products'))
                ->prepend($id)
                ->unique()
                ->slice(0, config('marketplace-laravel-sdk.products.recently_viewed_max'))
                ->toArray()
        ]);
    }

    /**
     * Get a single product by product & vendor slug, with optional relationships
     *
     * @param string $vendorSlug
     *      Vendor Slug
     * @param string $productSlug
     *      Product Slug
     * @param string $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor,variants.images'
     *
     * @return Collection
     */
    public function get(string $vendorSlug, string $productSlug, $with = null)
    {
        // call api
        $response = $this->http()->get($this->getPath() . '/vendor/' . $vendorSlug . '/product/' . $productSlug, [
            'with' => $with,
            'status' => 'published',
        ]);

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

    /**
     * Get a single product by id, with optional relationships
     *
     * @param int $id
     *      Product id
     * @param string $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor,variants.images'
     *
     * @return Collection
     */
    public function getById(int $id, $with = null)
    {
        // call api
        $response = $this->http()->get($this->getPath() . '/products/' . $id, [
            'with' => $with,
            'status' => 'published',
        ]);

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

    /**
     * Get a single product by id, with optional relationships
     *
     * @param string $slug
     *      Product slug
     * @param string $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor,variants.images'
     *
     * @return Collection
     */
    public function getBySlug(string $slug, $with = null)
    {
        // call api
        $response = $this->http()->get($this->getPath() . '/products/slug/' . $slug, [
            'with' => $with,
            'status' => 'published',
        ]);

        // not found
        if ($response->status() == 404) throw new Exception($response['message'], 404);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the product!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of all products across all vendors
     *
     * @param String $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor'
     *
     * @return Collection
     */
    public function list(
        String $with = null,
        Int $per_page = null,
        Int $page = null,
        Int $category = null,
        String $in = null,
    ) {
        // set up params
        $params = [
            'status'   => 'published',
        ];
        if ($with) $params['with'] = $with;
        if ($per_page) $params['paginate'] = $per_page;
        if ($page) $params['page'] = $page;
        if ($category) $params['category'] = $category;
        if ($in) $params['in'] = $in;

        // call
        $response = $this->http()->get($this->getPath() . '/products', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        // return $response->json()['data'] ? collect($response->json()['data']) : collect();
        if ($per_page) return $response->json() ? collect($response->json()) : collect();
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a list of products with filters and pagination
     *
     * @param String $with
     *      Comma-separated relationships to include in the api call - example: 'images,vendor'
     *
     * @return Collection
     */
    public function listWithFilters(
        String $with = null,
        Int $per_page = null,
        Int $page = null,
        array $attributes = null,
        array $vendors = null,
        array $categories = null,
        array $sort = null,
        String $searchTerm = null,
        array $tags = null,
        array $specifications = null,
        array $price = null,
    ) {
        $data = [
            'status'        => 'published',
            'with'          => $with,
            'mpe_paginate'  => $per_page,
            'page'          => $page,
        ];

        // only include params if they are set
        $attributes ? $data['attributes'] = $attributes : false;
        $vendors ? $data['vendors'] = $vendors : false;
        $categories ? $data['categories'] = $categories : false;
        $sort ? $data['sort'] = $sort : false;
        $searchTerm ? $data['searchTerm'] = $searchTerm : false;
        $tags ? $data['tags'] = $tags : false;
        $specifications ? $data['specifications'] = $specifications : false;
        $price ? $data['price'] = $price : false;

        // call
        $response = $this->http()->post($this->getPath() . '/products/filter', $data);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the products!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        // return $response->json()['data'] ? collect($response->json()['data']) : collect();
        if ($per_page) return $response->json() ? collect($response->json()) : collect();
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get the most recently added products across all vendors.
     *
     * @param Int $count
     *      (optional) The number of products to retrieve (default = 3).
     *
     * @return Collection
     */
    public function latest(Int $count = 3)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/products', [
            'sort' => 'created_at',
            'status' => 'published',
            'limit' => $count,
            'with' => 'images,vendor',
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to retrieve the latest products.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a given product's related products.
     *
     * TODO: WIP
     *
     * @param array $product The product array
     *
     * @param int $returnCount max number of results to return
     */
    public function related(array $product, Int $returnCount = 4)
    {
        // create empty products collection
        $products = collect();

        // first we check for product's cross-sells
        if ($product['has_cross_sells']) {

            // filter by published status
            $products = collect($product['cross_sells'])->where('status', 'published');
        }

        // if number of cross sells is greater than or equal to max return amount
        if ($products->count() >= $returnCount) {
            // return in random order limited to return count
            return $products->shuffle()->take($returnCount);
        }

        // if not enough cross sells…

        // get array of category ids from the product
        $categoryIDs = collect($product['categories'])->pluck('id')->toArray();

        // create exclusion list of this product's id and that of products already found
        $exclude = collect($product['id'])->merge($products->pluck('id'))->toArray();

        // loop over them
        foreach ($categoryIDs as $id) {

            // retrieve category and products
            $category = MPECategories::get($id, 'products.images,products.vendor');

            // exclude duplicates and restrict to amount required
            $categoryProducts = collect($category['products'])
                ->whereNotIn('id', $exclude)
                ->where('status', 'published')
                ->take($returnCount - $products->count());

            // merge into products collection
            $products = $products->merge($categoryProducts);

            // update exclusion list
            $exclude = collect($product['id'])->merge($products->pluck('id'))->toArray();

            // if enough products found
            if ($products->count() == $returnCount) {
                return $products;
            }
        }

        //     // return cross sells and however many more products are required, merged
        //     return $products = $products->merge($categoryProducts->random($returnCount - $products->count()))->unique('id');

        // } else {

        //     // else, add the products from this category to the collection and repeat with the next category
        //     $products = $products->merge($categoryProducts);

        //     // add new products to exclude
        //     $exclude = collect($exclude)->merge($categoryProducts->pluck('id'))->toArray();

        // }

        // get cross sells
        // count them
        // if less than 4
        // first category, exclude product and cross sells ids

        // $products->dd();
        // dd($exclude);

        // return the products
        return $products;
    }

    /**
     * Lookup product variant by code
     *
     * @param int $id
     *      Product ID
     * @param string $code
     *      Variant code e.g. '1.4-2.12-6.7'
     *
     * @return Collection
     */
    public function variantLookup(int $id, string $code)
    {
        // call
        $response = $this->http()->get($this->getPath() . '/product/' . $id . '/variants/lookup/' . $code);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to lookup the product variants!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
