<?php

namespace Code23\MarketplaceSDK\Traits;

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
