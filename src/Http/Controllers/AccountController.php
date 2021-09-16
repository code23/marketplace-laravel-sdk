<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function profile(Request $request)
    {
        // return
        return view('account.profile', [
            'user' => $request->user()
        ]);
    }
}
