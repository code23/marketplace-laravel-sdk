<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class BookingReviewService extends Service
{
    /**
     * Create a review
     */
    public function create(Array $data)
    {
        // call api
        $response = $this->http()->post($this->getPath() . '/booking-reviews', [
            'booking_id' => $data['booking_id'],
            'review'     => $data['review'],
            'rating'     => $data['rating'],
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to create review!!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Update a review
     */
    public function update(Array $data)
    {
        // call api
        $response = $this->http()->patch($this->getPath() . '/booking-reviews', [
            'id'         => $data['id'],
            'review'     => $data['review'],
            'rating'     => $data['rating'],
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to update review!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Delete a review
     *
     * @param Int $id
     * The review ID to delete.
     */
    public function delete(Int $id)
    {
        // call api
        $response = $this->http()->delete($this->getPath() . '/booking-reviews/' . $id);

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
     * By default returns all reviews.
     *
     * @param Array $ids
     *      model and id pairs to filter by. E.g. ['booking_id' => 1]
     *
     * @param Int $paginate
     *      Pagination items per page. Defaults to all.
     *
     * @param Int $page
     *      Pagination page to retrieve. Defaults to 1.
     *
     * @param String $with
     *      Relationships to include
     *
     * @param String $sort
     *      sort results by

     * @return Collection
     */
    public function list(Array $ids = [], $paginate = 0, $page = 1, $with = '', $sort = null)
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
        $response = $this->http()->get($this->getPath() . '/booking-reviews', $params);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the reviews!', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // return reviews list
        if (isset($params['paginate']) && $params['paginate']) return $response->json() ? collect($response->json()) : collect();
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
