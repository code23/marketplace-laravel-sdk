<?php

namespace App\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Http\Request;

class MPEPACAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // determine whether we have an authenticated user
        if (!$request->session()->has('oAuth')) {
            // authenticate site so we can access api endpoints
            MPEAuthentication::authenticateSite($request);
        }

        return $next($request);
    }
}
