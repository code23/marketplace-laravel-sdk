<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

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
        try {
            // retrieve currency options and store data to user session
            MPECurrencies::init();

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $next($request);
    }
}
