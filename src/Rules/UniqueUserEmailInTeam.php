<?php

namespace Code23\MarketplaceLaravelSDK\Rules;

use Code23\MarketplaceLaravelSDK\Facades\MPEUser;
use Illuminate\Contracts\Validation\Rule;

class UniqueUserEmailInTeam implements Rule
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
        // call to MPE to check email is unique within team
        $this->response = MPEUser::emailExistsInTeam($value);

        // if response gives true
        if (isset($this->response->json()['data']) && $this->response->json()['data'] === false) {
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
        return 'That email address is already in use' ?? 'Unable to check email is unique';
    }
}
