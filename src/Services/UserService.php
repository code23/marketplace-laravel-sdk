<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;

class UserService extends Service
{
    /**
     * retrieve user
     *
     * @return Authenticatable
     */
    public function get($id = null): User
    {
        // call
        $response = $this->http()->get($this->getPath() . '/user');

        // failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // return user as user model
        return static::auth((new User())->forceFill($response->json()['data']));
    }

    /**
     * authenticate the user on the consuming application
     *
     * @param User $user
     *
     * @return User
     */
    protected static function auth(User $user): User
    {
        static::bind($user);
        static::login($user);

        return $user;
    }

    /**
     * bind the response to the user and token singletons
     *
     * @param User $user
     *
     * @return void
     */
    protected static function bind(User $user): void
    {
        app()->bind('user', static function () use ($user): User {
            return $user;
        });

        app()->bind('token', static function (): string {
            return session()->get('oAuth')['access_token'];
        });
    }

    /**
     * login user using auth facade
     *
     * @param User $user
     *
     * @return void
     */
    protected static function login(Authenticatable $user): void
    {
        Auth::login($user, true);
    }

    /**
     * update the given user
     */
    public function update($userId)
    {
        # code...
    }
}
