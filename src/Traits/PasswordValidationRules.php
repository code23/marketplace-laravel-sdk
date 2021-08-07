<?php

namespace Code23\MarketplaceLaravelSDK\Traits;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', 'confirmed'];
    }
}
