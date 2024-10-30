<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class BookingCalendarService extends Service
{
    /**
     * Process enquiry
     */
    public function processEnquiry($vendor_id, $params = [])
    {
        $response = $this->http()->post($this->getPath() . '/booking-calendar/vendors/' . $vendor_id . '/enquiry', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to process enquiry : ' . $response->body(), 422);

        // errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Return a list of authenticated user's Booking Orders.
     *
     * @return Collection
     */
    public function list($params = [
        'with' => 'currency',
        'sort' => 'created_at,desc',
        'paginate' => 10,
    ])
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/booking-calendar/bookings', $params);

        // error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the booking orders!', 422);

        // return orders list
        return $response->json() ? collect($response->json()) : collect();
    }

    /**
     * Get a single booking order
     */
    public function get($id, $params = [])
    {
        $response = $this->http()->get($this->getPath() . '/booking-calendar/bookings/' . $id, $params);

        // error
        if ($response->failed()) throw new Exception('Unable to retrieve the booking order!', 422);

        // errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get availability slots
     */
    public function getAvailabilitySlots($vendor_id, $params = [])
    {
        $response = $this->http()->get($this->getPath() . '/booking-calendar/availabilities/vendors/' . $vendor_id . '/slots', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to process enquiry : ' . $response->body(), 422);

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
        $response = $this->http($oauth)->delete($this->getPath() . '/booking-calendar/bookings/' . $id, $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the booking cancellation.', 422);

        // return response
        return $response;
    }
}
