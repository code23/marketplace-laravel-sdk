<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class Service
{
    public $client;
    public $options = [
        'verify' => false,      // no ssl verification required
        'origin' => 'az-modesty.test',
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

    /**
     * validate
     */
    public function validator($request, $rules, $messages)
    {
        try {

            // run laravel validation using passed in rules and messages
            // throws an exception if validation fails
            $request->validate($rules, $messages);

            // if passes, return true
            return true;

        } catch (Exception $e) {

            // if the exception contains a validator object
            if ($e->validator) {

                // flash the errors array to the session
                $request->session()->flash(
                    'errors', $e->validator->messages(),
                );

                // flash the submitted field values for form re-population, except:
                $request->flashExcept([
                    'password',
                    'password_confirmation',
                    'image_1',
                    'image_2',
                    'image_3',
                    'image_4',
                ]);
            }

            // dd($request->session());
            // dd($e->getMessage());

            // return the exception message
            return $e->getMessage();
        }
    }
}
