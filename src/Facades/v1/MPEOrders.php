<?php

namespace Code23\MarketplaceLaravelSDK\Facades\v1;

use Code23\MarketplaceLaravelSDK\Services\v1\OrderService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Authenticatable get() return user
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\UserService
 */
abstract class MPEOrders extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OrderService::class;
    }
}
