<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Code23\MarketplaceSDK\Facades\MPEAuthentication;
use Code23\MarketplaceSDK\Traits\PasswordValidationRules;

use Exception;
use Illuminate\Contracts\View\View as ViewView;
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
            if (($response = MPEAuthentication::login($request)) !== true) {
                return redirect()->route('marketplace-sdk::auth.two-factor-login', [
                    'returnUrl' => $response['return_url'],
                ]);
            }

            return redirect()->route('welcome');

        } catch (Exception $e) {
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

        try {
            // request link
            $response = MPEAuthentication::resetPasswordLinkRequest($request->email);

            // flash session
            $request->session()->flash('status', $response->message);

            return view('marketplace-sdk::auth.login');

        } catch (Exception $e) {
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
        try {
            // update password
            $response = MPEAuthentication::updatePassword($request);

            // flash session
            $request->session()->flash('status', $response->message);

            return view('marketplace-sdk::auth.login');

        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * enable/disable two factor authentication
     */
    public function twoFactorAuthentication(Request $request, $state): View
    {
        try {
            // enable/disable
            $state == 'enable' ? $response = $request->user()->enable2FA() : $response = $request->user()->disable2FA();

            // flash session
            $request->session()->flash('status', $response['message']);

            return view('marketplace-sdk::auth.two-factor-authentication', [
                'recoveryCodes' => $response['recoveryCodes'],
                'svgQRCode'     => $response['svg_qr_code'],
            ]);
        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * two factor authentication code confirmation
     */
    public function twoFactorConfirmation(Request $request)
    {
        $response = MPEAuthentication::twoFactorConfirmation($request);

        dd($response);
    }
}
