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
}
