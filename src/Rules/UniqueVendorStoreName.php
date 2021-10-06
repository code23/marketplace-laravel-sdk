<?php

namespace Code23\MarketplaceLaravelSDK\Rules;

use Code23\MarketplaceLaravelSDK\Facades\MPEVendors;
use Illuminate\Contracts\Validation\Rule;

class UniqueVendorStoreName implements Rule
{
    private $response;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // call to MPE to check name is unique
        $this->response = MPEVendors::storeNameIsUnique($value);

        // if response gives true
        if(isset($this->response->json()['data']) && $this->response->json()['data'] === true) {
            // pass validation
            return true;
        }

        // fail
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // use error message from MPE or state the api call failed
        return $this->response->json()['message'] ?? 'Unable to check for vendor name unique';
    }
}
