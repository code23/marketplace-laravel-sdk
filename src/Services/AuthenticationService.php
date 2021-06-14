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
     * @param Request $request - must contain email and password
     *
     * @return Array
     */
    public function login(Request $request): array
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
        $this->setSession($response->json());

        // return user
        return $response->json();
    }

    /**
     * reset password link request
     *
     * @param String $email
     */
    public function resetPasswordLinkRequest($email): object
    {
        // send request
        $response = $this->http->post($this->getPath() . '/password/reset', [
            'email' => $email,
        ]);

        // failed
        if ($response->failed()) throw new Exception('Unable to request a password reset link!', 422);

        return $this->response([
            'message' => 'Reset link request sent!',
        ]);
    }

    /**
     * update password
     *
     * @param Request $request - must contain password and token
     */
    public function updatePassword(Request $request): object
    {
        // update password
        $response = $this->http->post($this->getPath() . '/password/reset', [
            'password'  => $request->password,
            'token'     => $request->token,
        ]);

        // failed
        if ($response->failed()) throw new Exception('Unable to update your password!', 422);

        return $this->response([
            'message' => 'Password updated!',
        ]);
    }

    /**
     * set session
     *
     * @param json $request - oAuth Passport token response
     */
    private function setSession($oAuth)
    {
        session()->put('oAuth', $oAuth);
    }
}
