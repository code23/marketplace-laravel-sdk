<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Facades\MPEUser;
use Exception;
use Illuminate\Http\Request;

class AuthenticationService extends Service
{
    /**
     * login
     *
     * @param Request $request - must contain email and password
     *
     * @return
     */
    public function login(Request $request)
    {
        // prepare payload
        $payload = [
            'grant_type'    => 'password',
            'client_id'     => config('marketplace-laravel-sdk.api.keys.id'),
            'client_secret' => config('marketplace-laravel-sdk.api.keys.secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '*'
        ];

        // retrieve oAuth tokens
        $response = $this->http->post($this->getAuthPath() . '/token', $payload);

        // http request failed
        if ($response->failed()) throw new Exception('A problem was encountered during the authentication process.', 422);

        // process error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // determine whether or not we have two factor authentication endpoint in the response
        if (isset($response['data']['challenged']) && $response['data']['challenged']) {
            return view('marketplace-laravel-sdk::auth.two-factor-login', [
                'return_url' => $response['data']['return_url'],
            ]);
        }

        // authenticate user
        $this->authenticateUser($response->json());

        // back to welcome - login succeeded
        return true;
    }

    /**
     * reset password link request
     *
     * @param String $email
     */
    public function resetPasswordLinkRequest($email): bool
    {
        // send request
        $response = $this->http->post($this->getPath() . '/password/reset', [
            'email' => $email,
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request for a password reset link.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return true;
    }

    /**
     * enable/disable two factor authentication
     */
    public function twoFactorAuthentication($state): array
    {
        // send request
        $response = $this->http->post($this->getPath() . '/auth/two-factor/' . $state);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered whilst attempting to ' . $state . ' two factor authentication on your account.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response->json()['data'] ?? ['message' => $response['message']];
    }

    /**
     * two factor confirmation
     */
    public function twoFactorValidation(Request $request): array
    {
        // define
        $type = 'code';

        // check we're passing a recovery code instead
        if ($request->authentication_type == 'recovery_code') $type = $request->authentication_type;

        // send request to signed url for confirmation
        $response = $this->http->post($request->return_url, [
            $type => $request->authentication_code,
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered during the process to confirm your identity.', 422);

        // process error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // authenticate user
        $this->authenticateUser($response->json());

        return $response->json();
    }

    /**
     * update password
     *
     * @param Request $request - must contain password and token
     */
    public function updatePassword(Request $request): bool
    {
        // update password
        $response = $this->http->post($this->getPath() . '/password/reset', [
            'password'  => $request->password,
            'token'     => $request->token,
        ]);

        // failed
        if ($response->failed()) throw new Exception('A problem was encountered during the process of updating your password.', 422);

        // process error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return true;
    }

    /**
     * authenticate the user by setting the session and retrieving the user from MPE
     */
    private function authenticateUser($oAuth): void
    {
        // set session
        session()->put('oAuth', $oAuth);

        // retrieve up-to-date user
        MPEUser::get();
    }
}
