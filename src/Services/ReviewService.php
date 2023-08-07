<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ReviewService extends Service
{
    /**
     * Delete a review
     *
     * @param Int $id
     *      The review ID to delete.
     */
    public function delete(Int $id)
    {
        // call api
        $response = $this->http()->delete($this->getPath() . '/reviews/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to delete the review!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return response
        return $response;
    }

    /**
     * Return a list of reviews.
     *
     * By default returns all reviews on the site.
     *
     * @param Array $ids
     *      model and id pairs to filter by. E.g. ['product_id' => 1] or ['profile_id' => 1, 'vendor_id' => 1]
     *
     * @param Int $paginate
     *      Pagination items per page. Defaults to all.
     *
     * @param Int $page
     *      Pagination page to retrieve. Defaults to 1.
     *
     * @param String $with
     *      Relationships to include, defaults to product with images
     *
     * @param String $sort
     *      sort results by

     * @return Collection
     */
    public function list(Array $ids = [], $paginate = 0, $page = 1, $with = 'product.images', $sort = null)
    {
        // create params & include relationships
        $params = ['with' => $with];

        // conditionally include provided IDs
        foreach ($ids as $key => $id) {
            $params[$key] = $id;
        }

        // paginate results
        if($paginate) $params['paginate'] = $paginate;
        if($page > 1) $params['page'] = $page;

        // sort results
        if($sort) $params['sort'] = $sort;

        // call to api
        $response = $this->http()->get($this->getPath() . '/reviews', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the reviews!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return reviews list
        return $response->json()['code'] == 200 ? collect($response->json()) : collect();
    }

    /**
     * Create a review
     */
    public function create(Array $data)
    {
        // call api
        $response = $this->http()->post($this->getPath() . '/reviews', [
            'product_id' => $data['product_id'],
            'review'     => $data['review'],
            'rating'     => $data['rating'],
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to create review!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return product list
        return $response;
    }

    /**
     * Update a review
     */
    public function update(Array $data)
    {
        // call api
        $response = $this->http()->patch($this->getPath() . '/reviews', [
            'id'         => $data['id'],
            'review'     => $data['review'],
            'rating'     => $data['rating'],
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to update review!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return product list
        return $response;
    }
}
