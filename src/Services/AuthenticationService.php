<?php

namespace Code23\MarketplaceSDK\Services;

use Code23\MarketplaceSDK\Facades\MPEUser;
use Exception;
use Illuminate\Http\Request;

class AuthenticationService extends Service
{
    /**
     * login
     *
     * @param $request - must contain email and password
     */
    public function login(Request $request): object
    {
        // prepare payload
        $payload = [
            'grant_type'    => 'password',
            'client_id'     => config('marketplace-sdk.api.keys.id'),
            'client_secret' => config('marketplace-sdk.api.keys.secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '*'
        ];

        // retrieve oAuth tokens
        $response = $this->http->post($this->getAuthPath() . '/token', $payload);

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve oAuth tokens!', 422);

        // set session
        $this->setSession($response);

        // return user
        return $this->response(MPEUser::get());
    }

    /**
     * set session
     */
    private function setSession($oAuth)
    {
        session()->put('oAuth', $oAuth);
    }
}
