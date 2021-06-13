<?php

namespace Code23\MarketplaceSDK\Facades;

use Code23\MarketplaceSDK\Services\AuthenticationService;
use Code23\MarketplaceSDK\Services\UserService;

use Illuminate\Support\Facades\Facade;

/**
 * @method static json get() return user
 *
 * @see \Code23\MarketplaceSDK\Services\UserService
 */
abstract class MPEUser extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mpe-user';
    }
}
