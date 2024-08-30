<?php

namespace Code23\MarketplaceLaravelSDK\Facades\v1;

use Code23\MarketplaceLaravelSDK\Services\v1\BookingService;
use Illuminate\Support\Facades\Facade;

abstract class MPEBookings extends Facade
{
	/**
	 * get the registered name of the component
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return BookingService::class;
	}
}
