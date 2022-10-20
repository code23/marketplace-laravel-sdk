<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPEAuthentication;
use Illuminate\Http\Request;

class MPESessionAuthentication
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
        // if session doesn't have oAuth data
        if (!$request->session()->has('oAuth')) {
            // authenticate site so we can access api endpoints
            MPEAuthentication::authenticateSite($request);
        }

        return $next($request);
    }
}
