<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class CollectionService extends Service
{
	/**
	 * Retrieve all collections
	 *
	 * @return Collection of vendors
	 */
	public function index(
		$params = [],
		$oauth = null,
	) {
		// retrieve collections
		$response = $this->http($oauth)->get($this->getPath() . '/collections', $params);

		// errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the vendors retrieval.', 422);

		// return the collections
		return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
	}

	public function show($id, $params = [], $oauth = null)
	{
		// retrieve collection
		$response = $this->http($oauth)->get($this->getPath() . '/collections/' . $id, $params);

		// errors
		if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

		// api call failed
		if ($response->failed()) throw new Exception('A problem was encountered during the collection retrieval.', 422);

		// return the collection
		return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
	}
}