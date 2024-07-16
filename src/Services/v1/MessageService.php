<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class MessageService extends Service
{
    /**
     * Get a list of channels
     *
     * @param  array  $params  the parameters to send with the request
     */
    public function getAllChannels(array $params = [])
    {
        // merge default params with user params
        $params = array_merge([
            'status' => 'open',
            'type' => null,
        ], $params);

        // send request
        $response = $this->http()->get($this->getPath().'/messaging', $params);

        // errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // if successful, return messages as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a channel
     *
     * @param  $channelId  the id of the channel to get
     * @param  $params  the parameters to send with the request
     */
    public function getChannel(string $channelId, array $params = [])
    {
        // merge default params with user params
        $params = array_merge(['paginate' => 30], $params);

        // send request
        $response = $this->http()->get($this->getPath().'/messaging/view/'.$channelId, $params);

        // errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // if successful, return messages as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Load more messages
     *
     * @param  $channelId  the id of the channel to get
     * @param  $params  the parameters to send with the request
     */
    public function loadMoreMessages(string $channelId, array $params = [])
    {
        // merge default params with user params
        $params = array_merge([
            'page' => 1,
            'paginate' => 30,
        ], $params);

        // send request
        $response = $this->http()->get($this->getPath().'/messaging/'.$channelId.'/messages', $params);

        // errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // if successful, return messages as collection
        return $response->json() ? collect($response->json()) : collect();
    }

    /**
     * Send a message
     *
     * @param  $params  the parameters to send with the request
     */
    public function sendMessage(array $params = [])
    {
        // merge default params with user params
        $params = array_merge([
            'order_id' => null,
            'event_id' => null,
            'is_update' => false,
        ], $params);

        // send request
        $response = $this->http()->post($this->getPath().'/messaging', $params);

        // errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // if successful, return messages as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Close a channel
     *
     * @param  $channelId  the id of the channel to close
     */
    public function closeChannel(string $channelId)
    {
        // send request
        $response = $this->http()->patch($this->getPath().'/messaging/close/'.$channelId);

        // errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // if successful, return messages as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
