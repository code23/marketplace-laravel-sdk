<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Facades\v1\MPECurrencies;
use Code23\MarketplaceLaravelSDK\Facades\MPEUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            'client_id'     => config('marketplace-laravel-sdk.api.password_keys.id'),
            'client_secret' => config('marketplace-laravel-sdk.api.password_keys.secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => ''
        ];

        // retrieve oAuth tokens
        $response = $this->http()->post($this->getAuthPath() . '/token', $payload);

        // http request failed
        // if ($response->failed()) throw new Exception('A problem was encountered during the authentication process.', 422);

        // any other error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

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
    public function resetPasswordLinkRequest($email)
    {
        // send request
        $response = $this->http()->post($this->getPath() . '/auth/forgot-password', [
            'email' => $email,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request for a password reset link.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response->json()['data'] ?? ['message' => $response['message']];
    }

    /**
     * enable/disable two factor authentication
     */
    public function twoFactorAuthentication($state): array
    {
        // send request
        $response = $this->http()->post($this->getPath() . '/auth/two-factor/' . $state);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to ' . $state . ' two factor authentication on your account.', 422);

        // any other error
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
        $response = $this->http()->post($request->return_url, [
            $type => $request->authentication_code,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the process to confirm your identity.', 422);

        // any other error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], $response['code']);

        // authenticate user
        $this->authenticateUser($response->json());

        return $response->json();
    }

    /**
     * update password
     *
     * @param Request $request - must contain email, password, password_confirmation and token
     */
    public function updatePassword(Request $request)
    {
        // update password
        $response = $this->http()->post($this->getPath() . '/auth/reset-password', [
            'email'  => $request->email,
            'password'  => $request->password,
            'password_confirmation'  => $request->password_confirmation,
            'token'     => $request->token,
        ]);

        return $response->json();

        // api call failed
        // if ($response->failed()) throw new Exception('A problem was encountered during the process of updating your password.', 422);

        // any other error
        // if ($response['errors']) throw new Exception($response['message'], 422);

        // return true;
    }

    /**
     * authenticate site
     */
    public function authenticateSite()
    {
        // check if we have a cached oAuth token
        if ($cached = Cache::get('oAuth')) {
            // save it to session
            session()->put('oAuth', $cached);

            return $cached;
        }

        // prepare payload
        $payload = [
            'grant_type'    => 'client_credentials',
            'client_id'     => config('marketplace-laravel-sdk.api.client_credential_keys.id'),
            'client_secret' => config('marketplace-laravel-sdk.api.client_credential_keys.secret'),
        ];

        // retrieve oAuth token
        $response = $this->http()->post($this->getAuthPath() . '/token', $payload);

        // any other error
        if (isset($response['error']) && $response['error']) throw new Exception($response['message'], 422);

        // save to session
        session()->put('oAuth', $response->json());

        // save to cache, set to expire 500s before it actually does
        Cache::put('oAuth', $response->json(), $response->json()['expires_in'] - 500);

        return $response->json();
    }

    /**
     * get the tenant's active modules
     */
    public function getModules($oauth = null)
    {
        // send request
        $response = $this->http($oauth)->get($this->getPath() . '/auth/modules');

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request for tenant modules', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * authenticate the user by setting the session and retrieving the user from MPE
     */
    private function authenticateUser($oAuth): void
    {
        // set session
        session()->put('oAuth', $oAuth);

        // retrieve up-to-date user
        $user = MPEUser::get();

        // activate preferred currency
        if($user && isset($user->profile['currency'])) {
            MPECurrencies::setActiveByCode($user->profile['currency']['code']);
        }
    }
}
