<?php

namespace Code23\MarketplaceLaravelSDK\Services\v1;

use Code23\MarketplaceLaravelSDK\Services\Service;
use Exception;

class QuoteService extends Service
{
    /**
     * Retrieve all quotes associated with the user
     *
     * @return Collection of quotes
     */
    public function list($params = [], $oauth = null)
    {
        // retrieve quotes
        $response = $this->http($oauth)->get($this->getPath() . '/quotes', $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the quotes retrieval.', 422);

        // return the quotes
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Retrieve a quote by ID
     */
    public function get($id, $params = [], $oauth = null)
    {
        // retrieve quote
        $response = $this->http($oauth)->get($this->getPath() . '/quotes/' . $id, $params);

        // errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the quote retrieval.', 422);

        // return the quote
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Decline a quote
     */
    public function decline($id, $params = [], $oauth = null)
    {
        $params = array_merge(['with' => ''], $params);

        $response = $this->http($oauth)->patch($this->getPath() . '/quotes/' . $id . '/decline', ['with' => $params['with']]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the quote retrieval.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the quote
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Request a quote
     */
    public function request($params = [], $oauth = null)
    {
        $response = $this->http($oauth)->get($this->getPath() . '/quotes/request', $params);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the quote request.', 422);

        // any other errors
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // return the quote
        return isset($response->json()['data']) ? collect($response->json()['data']) : collect();
    }

    /**
     * Process full payment
     */
    public function processFullPayment($id, $params = [])
    {
        $response = $this->http()->post($this->getPath() . '/quotes/' . $id . '/payment', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to process payment: ' . $response->body(), 422);

        // errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Process deposit payment
     */
    public function processDepositPayment($id, $params = [])
    {
        $response = $this->http()->post($this->getPath() . '/quotes/' . $id . '/payment/deposit', $params ?? null);

        // error
        if ($response->failed()) throw new Exception('Unable to to process deposit payment: ' . $response->body(), 422);

        // errors
        if (isset($response['error']) && $response['error'] == true) throw new Exception($response['message'], $response['code']);

        // if successful
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
