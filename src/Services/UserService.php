<?php

namespace Code23\MarketplaceSDK\Services;

use App\Models\User;

use Exception;
use Illuminate\Http\Request;

class UserService extends Service
{
    /**
     * retrieve user
     *
     * @return User $user
     */
    public function get(): User
    {
        // call
        $response = $this->http->get($this->getPath() . '/super-admin/show');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // return user as user model
        return (new User())->forceFill($response->json()['data']);
    }
}
