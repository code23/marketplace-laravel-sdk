<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use Exception;

class ProductService extends Service
{
    public function list()
    {
        // call
        $response = $this->http->get($this->getPath() . '/products');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // return product list
        return $response;
    }
}
