<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware\v1;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\v1\MPEStored;
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
            // if active_currency_code not in session
            if(!session('active_currency_code')) {
                // set to default currency for site
                session(['active_currency_code' => collect(MPEStored::currencies())->firstWhere('is_default', true)['code']]);
            }
        } catch (Exception $e) {
            Log::error($e);
        }

        return $next($request);
    }
}
