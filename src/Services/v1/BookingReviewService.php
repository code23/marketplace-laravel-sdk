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
}
