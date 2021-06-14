<?php

namespace Code23\MarketplaceSDK\Interfaces;

use Illuminate\Database\Eloquent\Model;

abstract class ModelInterface extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
}
