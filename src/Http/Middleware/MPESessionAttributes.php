<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPEAttributes;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESessionAttributes
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
            // if session has no attributes entry, OR if it does AND it was retrieved over X minutes ago
            if( session()->missing('attributes') || ( session()->has('attributes') && session('attributes')['retrieved_at']->lt(now()->subMinutes(config('marketplace-laravel-sdk.attributes.retrieval_rate'))) ) ) {

                // get the top-level attributes
                $response = MPEAttributes::list();

                // retrieve attributes and store data to user session
                session(['attributes' => [
                    'retrieved_at' => now(),
                    'data'         => $response->toArray(),
                ]]);

            }

        } catch (Exception $e) {
            Log::error($e);
        }

        return $next($request);
    }
}
