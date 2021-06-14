<?php

namespace Code23\MarketplaceSDK\Models;

use Code23\MarketplaceSDK\Interfaces\ModelInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends ModelInterface implements AuthenticatableContract
{
    use Authenticatable;

    /**
     * @param string
     */
    public function getAuthIdentifierName(): string
    {
        return 'token';
    }

    /**
     * @param string
     */
    public function getAuthIdentifier(): string
    {
        return $this->token;
    }

    public function getAuthPassword()
    {

    }

    public function getRememberToken()
    {

    }

    public function getRememberTokenName()
    {

    }

    public function setRememberToken($value)
    {

    }
}
