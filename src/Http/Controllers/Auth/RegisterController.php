<?php

namespace Code23\MarketplaceSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Code23\MarketplaceSDK\Facades\MPEAuthentication;
use Illuminate\Http\Request;

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
    public function register(Request $request)
    {
        // authenticate
        // $user = MPEAuthentication::login($request);

        // // return
        // return view('marketplace-sdk::auth.login', [
        //     'user' => $user,
        // ]);
    }
}
