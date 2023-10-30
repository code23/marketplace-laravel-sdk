<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class EventService extends Service
{
	/**
	 * Submit a new event
	 */
	public function save(array $data)
	{
		// send data
		$response = $this->http()->post($this->getPath() . '/events', $data);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during submitting the event application.', 422);

		// any other error
		if ($response['error']) throw new Exception($response['message'], $response['code']);

		return true;
	}

	/**
	 * Retrieve all events associated with the user
	 *
	 * @return Collection of events
	 */
	public function list(
		$params = [],
		$oauth = null,
	) {
		// retrieve vendors
		$response = $this->http($oauth)->get($this->getPath() . '/events', $params);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the events retrieval.', 422);

		// any other errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// return the events
		return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
	}

	/**
	 * Retrieve an event by ID	  
	 */
	public function get($id, $params = [], $oauth = null)
	{
		// retrieve event
		$response = $this->http($oauth)->get($this->getPath() . '/events/' . $id, $params);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the event retrieval.', 422);

		// any other errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// return the events
		return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
	}
}
