<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class MessageService extends Service
{
	/**
	 * Get a list of messages
	 */
	public function messages(string $status = 'open', string $type = null)
	{
		$params = [
			'status' => $status,
			'type' => $type
		];

		// send request
		$response = $this->http()->get($this->getPath() . '/messaging', $params);

		// api call failed
		if ($response->failed()) throw new Exception($response->body(), 422);

		// any other errors
		if ($response['error']) throw new Exception($response['message'], $response['code']);

		// if successful, return blog posts as collection
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}

	/**
	 * Get a list of messages
	 */
	public function sendMessage(string $channelName, string $recipientId, array $message, int $orderId = null, bool $isUpdate = false)
	{

		// send request
		$response = $this->http()->post($this->getPath() . '/messaging', [
			'channel_name' => $channelName,
			'recipient_id' => $recipientId,
			'message' => $message,
			'order_id' => $orderId,
			'is_update' => $isUpdate
		]);

		// api call failed
		if ($response->failed()) throw new Exception($response->body(), 422);

		// any other errors
		if ($response['error']) throw new Exception($response['message'], $response['code']);

		// if successful, return blog posts as collection
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}
}