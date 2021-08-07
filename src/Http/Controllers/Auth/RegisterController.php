<?php

namespace Code23\MarketplaceLaravelSDK\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Code23\MarketplaceLaravelSDK\Facades\MPERegistration;
use Code23\MarketplaceLaravelSDK\Traits\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use PasswordValidationRules;

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
        Validator::make($request->all(), [
            'agree_terms'   => ['required'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'password'      => $this->passwordRules(),
            'team_name'     => ['required', 'string', 'max:255'],
        ])->validate();

        // register
        $user = MPERegistration::register($request);

        // return
        return view('marketplace-sdk::auth.register', [
            'user' => $user,
        ]);
    }
}
