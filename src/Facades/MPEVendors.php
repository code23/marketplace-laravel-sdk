<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Code23\MarketplaceLaravelSDK\Services\VendorService;
use Illuminate\Support\Facades\Facade;

/**
 * Vendors facade
 * @see \Code23\MarketplaceLaravelSDK\Services\VendorService
 */
abstract class MPEVendors extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return VendorService::class;
    }
}
