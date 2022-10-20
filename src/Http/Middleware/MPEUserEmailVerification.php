<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MPEUserEmailVerification
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
        // restrict action to homepage for performance reasons
        if(Route::currentRouteName() == 'home') {
            // get email verified confirmation route name
            // - use env value EMAIL_VERIFIED_ROUTE_NAME to overwrite
            $routeName = config('marketplace-laravel-sdk.user.email_verified_route_name');
            // check for email_verified param in the url to trigger redirect
            if($request->has('email_verified')) return redirect(route($routeName));
        }

        return $next($request);
    }
}
