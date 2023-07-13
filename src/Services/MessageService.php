<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class MessageService extends Service
{
	/**
	 * Get a list of channels
	 * @param $status the status of the channels to get
	 * @param $type the type of the channels to get
	 */
	public function getAllChannels(string $status = 'open', string $type = null)
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
	 * Get a channel
	 * @param $channelId the id of the channel to get
	 */
	public function getChannel(String $channelId)
	{
		// send request
		$response = $this->http()->get($this->getPath() . '/messaging/view/' . $channelId);

		// api call failed
		if ($response->failed()) throw new Exception($response->body(), 422);

		// any other errors
		if ($response['error']) throw new Exception($response['message'], $response['code']);

		// if successful, return blog posts as collection
		return $response->json()['data'] ? collect($response->json()['data']) : collect();
	}

	/**
	 * Send a message
	 * @param $channelName the name of the channel to send the message to
	 * @param $recipientId the id of the recipient vendor
	 * @param $message the message to send, together with the files
	 * @param $orderId the id of the order to which the message is related
	 * @param $isUpdate whether the message is an update to an existing message
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
