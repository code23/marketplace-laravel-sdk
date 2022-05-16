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
     * @param Int $vendor_id
     *      (optional) The vendor ID to get reviews by.
     *
     * @return Collection
     */
    public function list(Int $product_id = null, Int $profile_id = null, Int $vendor_id = null, $with = 'product.images')
    {
        // create params & include relationships
        $params = ['with' => $with];

        // conditionally include provided IDs
        $product_id ? $params['product_id'] = $product_id : null;
        $profile_id ? $params['profile_id'] = $profile_id : null;
        $vendor_id ? $params['vendor_id'] = $vendor_id : null;

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
