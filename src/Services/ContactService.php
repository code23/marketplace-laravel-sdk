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
    public function submit(array $data)
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
    public function submitToVendor(array $data)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/contact-seller-form', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error attempting to submit the contact form.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Submit repairs form
     *
     * @param array $data Repairs form data
     *
     */
    public function submitServiceForm(array $data)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/contact-service-form', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error attempting to submit the form.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Submit private seller form
     *
     * @param array $data Private seller form data
     *
     */
    public function submitSalesForm(array $data)
    {
        // api call
        $response = $this->http()->post($this->getPath() . '/contact-sales-form', $data);

        // call failed
        if ($response->failed()) throw new Exception('Error attempting to submit the form.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }
}
