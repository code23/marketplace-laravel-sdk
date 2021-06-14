<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Code23\MarketplaceSDK\Facades\MPEAuthentication;
use Code23\MarketplaceSDK\Facades\MPEUser;
use Code23\MarketplaceSDK\Traits\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoginController extends Controller
{
    use PasswordValidationRules;

    /**
     * index
     */
    public function index(): View
    {
        return view('marketplace-sdk::auth.login');
    }

    /**
     * login to MPE
     *
     * @param Request $request
     */
    public function login(Request $request): View
    {
        // validate
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ])->validate();

        // authenticate
        MPEAuthentication::login($request);

        // retrieve user
        $user = MPEUser::get();

        // login user
        // Auth::login($user);

        return view('marketplace-sdk::auth.login', [
            'user' => $user,
        ]);
    }

    /**
     * forgotten password
     *
     * @param Request $request
     */
    public function passwordForgot(Request $request): View
    {
        return view('marketplace-sdk::auth.forgot-password', [
            'request' => $request
        ]);
    }

    /**
     * request password reset link via email
     *
     * @param Request $request
     */
    public function passwordEmail(Request $request): View
    {
        // validate
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();

        // request link
        $response = MPEAuthentication::resetPasswordLinkRequest($request->email);

        // flash session
        $request->session()->flash('status', $response->message);

        return view('marketplace-sdk::auth.login');
    }

    /**
     * password reset
     *
     * @param Request $request
     */
    public function passwordReset(Request $request): View
    {
        return view('marketplace-sdk::auth.reset-password', [
            'request' => $request
        ]);
    }

    /**
     * reset password with MPE
     *
     * @param Request $request
     */
    public function passwordUpdate(Request $request): View
    {
        // update password
        $response = MPEAuthentication::updatePassword($request);

        // flash session
        $request->session()->flash('status', $response->message);

        return view('marketplace-sdk::auth.login');
    }
}
