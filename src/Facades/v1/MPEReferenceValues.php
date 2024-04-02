<?php

namespace Code23\MarketplaceLaravelSDK\Facades\v1;

use Code23\MarketplaceLaravelSDK\Services\v1\ReferenceValuesService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Authenticatable get() return user
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\UserService
 */
abstract class MPEReferenceValues extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ReferenceValuesService::class;
    }
}
