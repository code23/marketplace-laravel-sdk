<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use App\Models\User;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Code23\MarketplaceLaravelSDK\Rules\UniqueUserEmailInTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserService extends Service
{
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
     * create new user
     */
    public function create(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'     => ['required', 'email', new UniqueUserEmailInTeam],
            'password'  => config('marketplace-laravel-sdk.passwords.rules'),
            'terms'     => 'required',
        ];

        $messages = [
            'password.regex' => 'Password must include at least one upper & lowercase letter.',
        ];

        // use our validation method in Service
        $validated = $this->validator($request->all(), $rules, $messages);

        if ($validated === true) {

            try {

                // send request
                $response = $this->http()->post($this->getPath() . '/customers/register', [
                    'first_name'            => $request->first_name,
                    'last_name'            => $request->last_name,
                    'email'                => $request->email,
                    'password'             => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'terms'                => isset($request->terms) ? true : false,
                ]);

                // api call failed
                if ($response->failed()) throw new Exception('A problem was encountered during the request to create a new user.', 422);

                // any other error
                if ($response['error']) throw new Exception($response['message'], $response['code']);

                // return
                return true;

            } catch (Exception $e) {

                return $e;
            }
        } else {
            return $validated;
        }
    }

    /**
     * delete user
     *
     * @return bool
     */
    public function delete()
    {
        try {
            // call
            $response = $this->http()->delete($this->getPath() . '/user');

            // api call failed
            if ($response->failed()) throw new Exception('Unable to delete the user!', 422);

            // any other error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            // logout
            auth()->logout();

            // clear session
            session()->flush();

            // return success
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Check user is not already registered with this tenant
     */
    public function emailIsUniqueInTeam($email)
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/tenants/has-user-with-email', [
            'email' => $email,
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('A problem was encountered during the request to check for existing user.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        return $response;
    }

    /**
     * Get the brands the user is following
     */
    public function follows()
    {
        // call
        $response = $this->http()->get($this->getPath() . '/user', [
            'with' => 'profile',
        ]);

        // user not found
        if ($response->status() == 404) throw new Exception('User not found!', 404);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // return followed vendors as collection
        return isset($response->json()['data']['profile']['follows']) ? collect($response->json()['data']['profile']['follows']) : collect();
    }

    /**
     * retrieve user
     *
     * @return Authenticatable
     */
    public function get($id = null): User
    {
        // call
        $response = $this->http()->get($this->getPath() . '/user', [
            'with' => 'profile'
        ]);

        // api call failed
        if ($response->failed()) throw new Exception('Unable to retrieve the user!', 422);

        // return user as user model
        return static::auth((new User())->forceFill($response->json()['data']));
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
     * update the given user's profile - first name, last name, password
     */
    public function updateProfile(Array $data)
    {
            // call
            $response = $this->http()->patch($this->getPath() . '/user', [
                'first_name'            => $data['first_name'],
                'last_name'            => $data['last_name'],
                'password'             => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'currency_id'          => $data['currency_id'],
            ]);

            // api call failed
            if ($response->failed()) throw new Exception('Unable to edit the user!', 422);

            // any other error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            // return success
            return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Send an email verification link to the user
     */
    public function sendEmailVerificationLink()
    {
        try {

            // call api
            $response = $this->http()->post($this->getPath() . '/auth/email/verification-notification/');

            // api call failed
            if ($response->failed()) throw new Exception('Unable to send verification email', 422);

            // any other error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            return 'Verification email sent';

        } catch (Exception $e) {

            Log::error($e->getMessage());

            return $e->getMessage();

        }
    }

    /**
     * Get the user's wishlist
     */
    public function wishlist()
    {
        // call to api
        $response = $this->http()->get($this->getPath() . '/wishlist');

        // api call failed
        if ($response->failed()) throw new Exception('Error getting the wishlist', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Add a product to the authed user's wishlist
     */
    public function wishlistAdd(int $id)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/wishlist/add/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to add product to wishlist', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }

    /**
     * Remove a product from the authed user's wishlist
     */
    public function wishlistRemove(int $id)
    {
        // call to api
        $response = $this->http()->patch($this->getPath() . '/wishlist/remove/' . $id);

        // api call failed
        if ($response->failed()) throw new Exception('Error attempting to remove product from wishlist.', 422);

        // any other error
        if ($response['error']) throw new Exception($response['message'], $response['code']);

        // if successful, return collection of products or empty collection
        return $response->json()['data'] ? collect($response->json()['data']) : collect();
    }
}
