<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Code23\MarketplaceLaravelSDK\Facades\MPEUser;
use Exception;
use Illuminate\Http\Request;

class RegistrationService extends Service
{
    /**
     * register
     *
     * @param $request - must contain first name, last name, team name, email, password & confirmation, terms
     */
    public function register(Request $request): object
    {
        // prepare payload
        $payload = [
            'email'                 => $request->email,
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'team_name'             => $request->team_name,
            'terms'                 => $request->agree_terms,
        ];

        // register new tenant
        $response = $this->http->post($this->getPath() . '/tenant/register', $payload);

        // failed
        if ($response->failed()) throw new Exception('Unable to register tenant!', 422);

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
