<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class BookingService extends Service
{
    /**
     * Retrieve all bookings associated with the user
     *
     * @return Collection of bookings
     */
    public function list($params = [], $oauth = null)
    {
        // retrieve bookings
        $response = $this->http($oauth)->get($this->getPath() . '/bookings', $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the bookings retrieval.', 422);

        // return the bookings
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve a booking by ID
     */
    public function get($id, $params = [], $oauth = null)
    {
        // retrieve booking
        $response = $this->http($oauth)->get($this->getPath() . '/bookings/' . $id, $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the booking retrieval.', 422);

        // return the booking
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Decline a booking
     */
    public function decline($id, $params = [], $oauth = null)
    {
        $params = array_merge(['with' => ''], $params);

        $response = $this->http($oauth)->patch($this->getPath() . '/bookings/' . $id . '/decline', ['with' => $params['with']]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the booking retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the booking
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Request a booking
     */
    public function request($params = [], $oauth = null)
    {
        $response = $this->http($oauth)->get($this->getPath() . '/bookings/request', $params);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the booking request.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the booking
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Process remaining payment
     */
    public function processRemainingPayment($id, $params = [])
    {
        $response = $this->http()->post($this->getPath() . '/bookings/' . $id . '/payment', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to process payment for booking: ' . $response->body(), 422);

        // errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
	 * Cancel a booking
	 */
	public function cancel($id, $params = [], $oauth = null)
	{
		$response = $this->http($oauth)->delete($this->getPath() . '/bookings/' . $id, $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the booking cancellation.', 422);

		// return response
		return $response;
	}
}
