<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static json register(Request $request) register new tenant user and return
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\RegistrationService
 */
class MPERegistration extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mpe-registration';
    }
}
