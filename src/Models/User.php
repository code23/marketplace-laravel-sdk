<?php

namespace App\Models;

use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    /**
     * @var array
     */
    protected $guarded = [];

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): string
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @param String $value
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * enable 2FA
     */
    public function enable2FA()
    {
        return MPEAuthentication::twoFactorAuthentication('enable');
    }

    /**
     * disable 2FA
     */
    public function disable2FA()
    {
        return MPEAuthentication::twoFactorAuthentication('disable');
    }
}
