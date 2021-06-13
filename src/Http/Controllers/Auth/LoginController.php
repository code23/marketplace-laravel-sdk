<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginForm;
use Code23\MarketplaceSDK\Facades\MPEAuthentication;

class LoginController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        return view('marketplace-sdk::auth.login');
    }

    /**
     * login to MPE
     */
    public function login(LoginForm $request)
    {
        // authenticate
        $user = MPEAuthentication::login($request);

        // return
        return view('marketplace-sdk::auth.login', [
            'user' => $user,
        ]);
    }
}
