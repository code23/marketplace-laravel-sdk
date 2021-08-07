<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Illuminate\Support\Facades\Http;

class Service
{
    public $http;
    private  $options = [
        'verify' => false,      // no ssl verification required
    ];

    public function __construct()
    {
        // determine whether tokens already exist
        if (session()->has('oAuth') && isset(session()->get('oAuth')['access_token'])) {
            // merge options
            $this->options = array_merge($this->options, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . session()->get('oAuth')['access_token'],
                    ],
                ]);
        }

        // new http client with options expecting json from the api
        $this->http = Http::withOptions($this->options)->acceptJson();
    }

    /**
     * get api segment
     */
    public function getApiSegment()
    {
        return '/api/' . config('marketplace-sdk.api.version');
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
        return config('marketplace-sdk.api.base_path', 'mpe.test');
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
}
