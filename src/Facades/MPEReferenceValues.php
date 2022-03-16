<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Code23\MarketplaceLaravelSDK\Services\ReferenceValuesService;
use Illuminate\Support\Facades\Facade;

/**
 * Vendors facade
 * @see \Code23\MarketplaceLaravelSDK\Services\VendorService
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
