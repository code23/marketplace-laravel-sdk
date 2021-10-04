<?php

namespace Code23\MarketplaceLaravelSDK\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;
use Illuminate\Http\Request;

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
     * create new user
     */
    public function create(Request $request)
    {
        $rules = [
            'first_name'         => 'required',
            'last_name'          => 'required',
            'email'              => 'required|email',
            'password'           => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/',
            'terms'              => 'required',
        ];

        $messages = [
            'password.regex' => 'Password must include at least one upper & lowercase letter.',
        ];

        // use our validation method in Service
        $validated = $this->validator($request, $rules, $messages);

        if ($validated) {

            try {

                // send request
                $response = $this->http()->post($this->getPath() . '/user/register', [
                    'first_name'              => $request->first_name,
                    'last_name'               => $request->last_name,
                    'email'                   => $request->email,
                    'password'                => $request->password,
                    'password_confirmation'   => $request->password_confirmation,
                    'terms'                   => isset($request->terms) ? true : false,
                ]);

                // failed
                if ($response->failed()) throw new Exception('A problem was encountered during the request for a password reset link.', 422);

                // process error
                if ($response['error']) throw new Exception($response['message'], $response['code']);

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
     * @param int $id User id to delete
     *
     * @return bool
     */
    public function delete($id)
    {
        try {
            // call
            $response = $this->http()->delete($this->getPath() . '/user/' . $id);

            // failed
            if ($response->failed()) throw new Exception('Unable to delete the user!', 422);

            // process error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            // return success
            return true;
        } catch (Exception $e) {
            return $e;
        }
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
    public function updateProfile(Request $request, $id)
    {
        try {
            // call
            $response = $this->http()->patch($this->getPath() . '/user/' . $id, [
                'first_name' => $request->first_name ?? $request->user()->first_name,
                'last_name'  => $request->last_name ?? $request->user()->last_name,
                'email'      => $request->user()->email,
            ]);

            // failed
            if ($response->failed()) throw new Exception('Unable to edit the user!', 422);

            // process error
            if ($response['error']) throw new Exception($response['message'], $response['code']);

            // return success
            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }
}
