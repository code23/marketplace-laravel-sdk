<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Code23\MarketplaceLaravelSDK\Services\PaymentMethodService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Authenticatable get() return user
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\UserService
 */
abstract class MPEPaymentMethods extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PaymentMethodService::class;
    }
}
