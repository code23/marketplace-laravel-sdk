<?php

namespace Code23\MarketplaceSDK\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as AuthProvider;
use Code23\MarketplaceSDK\Facades\MPEUser;

class UserProviderService implements AuthProvider
{
    public function retrieveById($identifier): Authenticatable
    {
        return MPEUser::get($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
    }
}
