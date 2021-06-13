<?php

namespace Code23\MarketplaceSDK\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static json login(Request $request) authenticate with mpe and return user
 *
 * @see \Code23\MarketplaceSDK\Services\AuthenticationService
 */
class MPEAuthentication extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mpe-authentication';
    }
}
