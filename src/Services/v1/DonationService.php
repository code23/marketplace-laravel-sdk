<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class DonationService extends Service
{
	/**
	 * Process payment
	 */
	public function save($charity_id, $data = [])
	{
		$response = $this->http()->post($this->getPath() . '/charities/' . $charity_id . '/donate', $data);

		// error
		if ($response->failed()) throw new Exception('Unable to create donation: ' . $response->body(), 422);

		// any other errors
		if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

		// if successful
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}

	/**
	 * Get donation by number
	 */
	public function getByNumber($donation_number, $params = [])
	{
		$response = $this->http()->get($this->getPath() . '/donations/number/' . $donation_number, $params);

		// error
		if ($response->failed()) throw new Exception('Unable to get donation: ' . $response->body(), 422);

		// any other errors
		if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

		// if successful
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}

	/**
	 * Get donations 
	 */
	public function list($params = [])
	{
		$response = $this->http()->get($this->getPath() . '/donations', $params);

		// error
		if ($response->failed()) throw new Exception('Unable to get donations: ' . $response->body(), 422);

		// any other errors
		if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

		// if successful
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}
}
