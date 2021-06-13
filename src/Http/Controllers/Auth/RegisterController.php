<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationForm;
use Code23\MarketplaceSDK\Facades\MPERegistration;

class RegisterController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        return view('marketplace-sdk::auth.register');
    }

    /**
     * login to MPE
     */
    public function register(RegistrationForm $request)
    {
        // register
        $user = MPERegistration::register($request);

        // return
        return view('marketplace-sdk::auth.register', [
            'user' => $user,
        ]);
    }
}
