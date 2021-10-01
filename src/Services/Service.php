<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Illuminate\Support\Facades\Http;

class Service
{
    public $client;
    public $options = [
        'verify' => false,      // no ssl verification required
    ];
    public $headers = [];

    /**
     * initialises a new http instance and returns the client
     */
    public function http()
    {
        // update headers with origin
        $this->headers = array_merge($this->headers, [
            'X-MPE-Origin' => request()->header('host') ?? config('marketplace-laravel-sdk.app.url'),
        ]);

        // new http client with options expecting json from the api
        $this->client = Http::withHeaders($this->headers)->withOptions($this->options)->acceptJson();

        // determine whether tokens already exist
        if (session()->has('oAuth') && isset(session('oAuth')['access_token'])) {
            // add bearer token
            $this->client->withToken(session('oAuth')['access_token']);
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
