<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Code23\MarketplaceLaravelSDK\Services\ImageService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Authenticatable get() return user
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\UserService
 */
abstract class MPEImages extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ImageService::class;
    }
}
