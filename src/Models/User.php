<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param string
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * @param string
     */
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

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }
}
