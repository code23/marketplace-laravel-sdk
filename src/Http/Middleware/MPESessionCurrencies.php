<?php

namespace App\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPECurrencies;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESessionCurrencies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if the session has no currency data (e.g. new session)
        if(!session('currencies')) {
            try {
                // retrieve currency options and store data to user session
                // sets the default currency as active
                MPECurrencies::reset();

                // if a user is logged in
                if($request->user()) {
                    // get their profile's preferred currency
                    $userCurrencyCode = $request->user()->profile['currency']['code'];

                    // set that currency as active
                    MPECurrencies::setActive($userCurrencyCode);
                }

            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        return $next($request);
    }
}
