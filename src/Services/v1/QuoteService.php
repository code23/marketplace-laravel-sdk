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
    public function list(
        $params = [],
        $oauth = null,
        ) {
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
         * Retrieve an quote by ID
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
         * Decline an quote
         */
        public function decline($id, $params = [], $oauth = null)
        {
            // @TODO: implement
        }
    }
