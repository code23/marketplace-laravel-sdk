<?php

namespace Code23\MarketplaceSDK\Services;

use Exception;
use Illuminate\Http\Request;

class UserService extends Service
{
    /**
     * retrieve user
     *
     * @return User $user
     */
    public function get()
    {
        // call
        $response = $this->http->get($this->getPath() . '/super-admin/show');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // response
        return $this->response($response->json());
    }
}
