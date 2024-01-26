<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Service
{
    public $client;
    public $headers = [];

    /**
     * initialises a new http instance and returns the client
     */
    public function http($oauth = null, $contentType = 'application/json')
    {
        // update headers with origin
        $this->headers = array_merge($this->headers, [
            'X-MPE-Origin' => config('marketplace-laravel-sdk.http.origin'),
        ]);

        // update headers with active currency code
        if(session()->has('active_currency_code')) {
            $this->headers = array_merge($this->headers, [
                'X-Currency-Code' => session('active_currency_code'),
            ]);
        }

        // update headers with active locale id
        $this->headers = array_merge($this->headers, [
            'X-Locale-Id' => 1,
        ]);

        // check session for cart id
        if (session('cart_id')) {
            $this->headers = array_merge($this->headers, [
                'X-Cart-Id' => session('cart_id'),
            ]);
        }

        // new http client with options expecting json from the api
        $this->client = Http::timeout(60)->withHeaders($this->headers)->withOptions($this->getOptions())->accept($contentType);

        // determine whether tokens already exist
        if (session()->has('oAuth') && isset(session('oAuth')['access_token'])) {
            // add bearer token
            $this->client->withToken(session('oAuth')['access_token']);
        } elseif ($oauth) {
            // add bearer token
            $this->client->withToken($oauth['access_token']);
        }

        return $this->client;
    }

    /**
     * get api segment
     */
    public function getApiSegment()
    {
        return '/api/' . config('marketplace-laravel-sdk.api.version');
    }

    /**
     * get auth segment
     */
    public function getAuthPath()
    {
        return $this->getBasePath() . '/oauth';
    }

    /**
     * get api base path
     */
    public function getBasePath()
    {
        return config('marketplace-laravel-sdk.api.base_path', 'mpe.test');
    }

    /**
     * get options
     */
    public function getOptions()
    {
        if (!config('marketplace-laravel-sdk.http.origin')) {
            Log::error('MPE_ORIGIN value missing from env!');
            throw new Exception("MPE_ORIGIN value missing from env", 500);
        }

        return [
            'verify' => false,      // no ssl verification required
            'origin' => config('marketplace-laravel-sdk.http.origin'),
        ];
    }

    /**
     * get full path
     */
    public function getPath()
    {
        return $this->getBasePath() . $this->getApiSegment();
    }

    /**
     * return json response as an object
     */
    public function response($response)
    {
        return json_decode(json_encode($response));
    }

    /**
     * validate
     */
    public function validator($data, $rules, $messages)
    {
        try {

            // validate data
            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                return $validator->errors();
            }

            // if passes validation
            return true;
        } catch (Exception $e) {

            // if the exception contains a validator object
            if ($e->validator) {
                // return error messages array
                return ['errors' => $e->validator->messages()];
            }

            // else, return the exception message
            return $e->getMessage();
        }
    }
}
