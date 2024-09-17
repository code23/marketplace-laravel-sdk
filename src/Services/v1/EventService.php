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

		// error
		if ($response['error']) throw new Exception($response['message'], $response['code']);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during submitting the event application.', 422);

		return $response['data'];
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
		// retrieve events
		$response = $this->http($oauth)->get($this->getPath() . '/events', $params);

		// errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the events retrieval.', 422);

		// return the events
		if (isset($params['paginate']) && $params['paginate']) return $response->json() ? collect($response->json()) : collect();
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}

	/**
	 * Retrieve an event by ID
	 */
	public function get($id, $params = [], $oauth = null)
	{
		// retrieve event
		$response = $this->http($oauth)->get($this->getPath() . '/events/' . $id, $params);

		// errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the event retrieval.', 422);

		// return the event
		return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
	}

	/**
	 * Cancel an event
	 */
	public function cancel($id, $params = [], $oauth = null)
	{
		// cancel event
		$response = $this->http($oauth)->delete($this->getPath() . '/events/' . $id, $params);

		// errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the event cancellation.', 422);

		// return response
		return $response;
	}
}
