<?php

namespace Code23\MarketplaceLaravelSDK\Facades;

use Code23\MarketplaceLaravelSDK\Services\AuthenticationService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static User login(Request $request) authenticate with mpe and return user
 * @method static object resetPasswordLinkRequest(String $email) request a password reset link for the email provided
 * @method static object setSession(json $oAuth) save the oAuth token response to the session
 * @method static object updatePassword(String $email) update the password for the email provided
 *
 * @see \Code23\MarketplaceLaravelSDK\Services\AuthenticationService
 */
class MPEAuthentication extends Facade
{
    /**
     * get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthenticationService::class;
    }
}
