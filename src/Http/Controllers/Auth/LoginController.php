<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Code23\MarketplaceSDK\Facades\MPEAuthentication;
use Code23\MarketplaceSDK\Traits\PasswordValidationRules;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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
    public function login(Request $request): RedirectResponse
    {
        // validate
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ])->validate();

        try {
            // authenticate
            $user = MPEAuthentication::login($request);
        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }

        // flash session
        $request->session()->flash('status', 'Welcome ' . $user->first_name);

        return redirect()->route('welcome');
    }

    /**
     * logout of MPE
     */
    public function logout(): RedirectResponse
    {
        // logout
        auth()->logout();

        // clear session
        session()->flush();

        return redirect()->route('welcome');
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
