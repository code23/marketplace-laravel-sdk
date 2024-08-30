<?php

namespace Code23\MarketplaceLaravelSDK\Facades\v1;

use Code23\MarketplaceLaravelSDK\Services\v1\EventService;
use Illuminate\Support\Facades\Facade;

abstract class MPEEvents extends Facade
{
	/**
	 * get the registered name of the component
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return EventService::class;
	}
}
