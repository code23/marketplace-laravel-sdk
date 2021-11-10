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

        // return product list
        return $response;
    }

    /**
     * Return a list of reviews.
     *
     * By default returns all reviews on the site.
     *
     * @param Int $product_id
     *      (optional) The product ID to get reviews for.
     *
     * @param Int $profile_id
     *      (optional) The user profile ID to get reviews by.
     *
     * @return Collection
     */
    public function list(Int $product_id = null, Int $profile_id = null)
    {
        // create params - include products & images
        $params = ['with' => 'product.images'];

        // conditionally include provided IDs
        if($product_id) $params['product_id'] = $product_id;
        if($profile_id) $params['profile_id'] = $profile_id;

        // call to api
        $response = $this->http()->get($this->getPath() . '/reviews', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the reviews!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return reviews list
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
