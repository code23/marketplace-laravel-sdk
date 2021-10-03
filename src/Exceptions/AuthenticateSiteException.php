<?php

namespace App\Exceptions;

use Exception;

class AuthenticateSiteException extends Exception
{
    public function render($request)
    {
        return view('errors.auth-failed', [
            'message' => 'Unable to authenticate with MPE!',
            'status' => 422
        ]);
    }
}
