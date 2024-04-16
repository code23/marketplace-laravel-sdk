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
    public function getAllChannels(array $params = [
        'status' => 'open',
        'type' => null,
    ])
    {
        // send request
        $response = $this->http()->get($this->getPath().'/messaging', $params);

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // any other errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // if successful, return blog posts as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Get a channel
     *
     * @param  $channelId  the id of the channel to get
     * @param  $params  the parameters to send with the request
     */
    public function getChannel(string $channelId, array $params = ['paginate' => 30])
    {
        // send request
        $response = $this->http()->get($this->getPath().'/messaging/view/'.$channelId, $params);

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // any other errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // if successful, return blog posts as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Load more messages
     *
     * @param  $channelId  the id of the channel to get
     * @param  $params  the parameters to send with the request
     */
    public function loadMoreMessages(string $channelId, array $params = [
        'page' => 1,
        'paginate' => 30,
    ])
    {
        // send request
        $response = $this->http()->get($this->getPath().'/messaging/'.$channelId.'/messages', $params);

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // any other errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // if successful, return blog posts as collection
        return $response->json() ? collect($response->json()) : collect();
    }

    /**
     * Send a message
     *
     * @param  $params  the parameters to send with the request
     */
    public function sendMessage(array $params = [
        'order_id' => null,
        'is_update' => false,
    ])
    {
        // send request
        $response = $this->http()->post($this->getPath().'/messaging', $params);

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // any other errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // if successful, return blog posts as collection
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

        // api call failed
        if ($response->failed()) {
            throw new Exception($response->body(), 422);
        }

        // any other errors
        if ($response['error']) {
            throw new Exception($response['message'], $response['code']);
        }

        // if successful, return blog posts as collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
