<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ContactService extends Service
{
    /**
     * Submit contact form to Tenant
     *
     * @param array $data Contact form data
     *
     */
    public function submit(Array $data)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/contact-form', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error attempting to submit the contact form.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Submit contact form to Vendor
     *
     * @param array $data Contact form data
     *
     */
    public function submitToVendor(Array $data)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/contact-seller-form', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error attempting to submit the contact form.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
