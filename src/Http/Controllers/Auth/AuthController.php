<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Code23\MarketplaceLaravelSDK\Traits\PasswordValidationRules;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    use PasswordValidationRules;

    /**
     * index
     */
    public function index(): View
    {
        return view('marketplace-laravel-sdk::auth.login');
    }

    /**
     * login to MPE
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        // validate
        Validator::make($request->all(), [
            'email'     => ['required', 'string', 'email', 'max:255'],
            'password'  => ['required', 'string'],
        ])->validate();

        try {
            // attempt login
            MPEAuthentication::login($request);

            // return to on success
            return redirect()->route('home');

        } catch (Exception $e) {
            // return with error message
            return back()->with('status', $e->getMessage());
        }
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

        // return to on success
        return redirect()->route('home');
    }

    /**
     * forgotten password
     *
     * @param Request $request
     */
    public function passwordForgot(Request $request): View
    {
        return view('marketplace-laravel-sdk::auth.forgot-password', [
            'request' => $request
        ]);
    }

    /**
     * request password reset link via email
     *
     * @param Request $request
     */
    public function passwordEmail(Request $request)
    {
        // validate
        Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();

        try {
            // request link
            $response = MPEAuthentication::resetPasswordLinkRequest($request->email);

            // flash session
            $request->session()->flash('status', $response['message']);

            // return to on success
            return redirect('mls.login');

        } catch (Exception $e) {
            // return with error message
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * password reset
     *
     * @param Request $request
     */
    public function passwordReset(Request $request): View
    {
        return view('marketplace-laravel-sdk::auth.reset-password', [
            'request' => $request
        ]);
    }

    /**
     * reset password with MPE
     *
     * @param Request $request
     */
    public function passwordUpdate(Request $request)
    {
        try {
            // update password
            $response = MPEAuthentication::updatePassword($request);

            // handle errors
            if(isset($response['errors']) && !empty($response['errors'])) return back()->with('status', $response['errors']);
            if(isset($response['error']) && $response['error'] == true) return back()->with('status', $response['message']);

            // flash session
            $request->session()->flash('status', $response['message']);

            // return to on success
            return view('marketplace-laravel-sdk::auth.login');

        } catch (Exception $e) {
            // return with error message
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * enable/disable two factor authentication
     */
    public function twoFactorAuthentication(Request $request, $state)
    {
        try {
            // enable/disable
            $state == 'enable' ? $response = $request->user()->enable2FA() : $response = $request->user()->disable2FA();

            // if enabling
            if ($state == 'enable') {
                return redirect()->route('two-factor.confirmation')->with('auth', $response);
            }

            // flash session
            $request->session()->flash('status', $response['message']);

            // return with error message
            return redirect()->route('profile');

        } catch (Exception $e) {
            // return with error message
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * show two factor qr and recovery codes
     */
    public function twoFactorDetails(Request $request)
    {
        return view('marketplace-laravel-sdk::auth.two-factor-authentication', [
                'auth' => session()->get('auth'),
            ]);
    }

    /**
     * two factor authentication code confirmation
     */
    public function twoFactorValidation(Request $request)
    {
        try {
            // validate two factory authentication code
            MPEAuthentication::twoFactorValidation($request);

            return redirect()->route('welcome');

        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }
    }
}
