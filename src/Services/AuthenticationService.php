<?php

namespace Code23\MarketplaceSDK\Services;

use App\Models\User;
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
    public function login(Request $request)
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
        if ($response->failed()) throw new Exception('Please check your username and password.', 422);

        // determine whether or not we have two factor authentication endpoint in the response
        if (isset($response['challenged'])) return $response->json();

        // authenticate user
        $this->authenticateUser($response->json());

        return true;
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
        if ($response->failed()) throw new Exception('Unable to request a password reset link.', 422);

        return $this->response([
            'message' => 'Reset link request sent!',
        ]);
    }

    /**
     * enable/disable two factor authentication
     */
    public function twoFactorAuthentication($state): array
    {
        // send request
        $response = $this->http->post($this->getPath() . '/auth/two-factor/' . $state);

        // failed
        if ($response->failed()) throw new Exception('Unable to enable two factor authentication on your account.', 422);

        return $response->json();
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
        if ($response->failed()) throw new Exception('Unable to confirm your identity.', 422);

        // authenticate user
        $this->authenticateUser($response->json());

        return $response->json();
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
        if ($response->failed()) throw new Exception('Unable to update your password.', 422);

        return $this->response([
            'message' => 'Password updated!',
        ]);
    }

    /**
     * authenticate the user by setting the session and retrieving the user from MPE
     */
    private function authenticateUser($oAuth)
    {
        // set session
        session()->put('oAuth', $oAuth);

        // retrieve up-to-date user
        MPEUser::get();
    }
}
