<?php

namespace Code23\MarketplaceSDK\Traits;

use Illuminate\Auth\Authenticatable;

trait MPEAuthenticatable
{
    use Authenticatable;

    /**
     * @var array
     */
    protected $guarded = [];

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
}
