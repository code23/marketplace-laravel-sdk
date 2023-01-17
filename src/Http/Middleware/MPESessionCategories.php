<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPECategories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESessionCategories
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
            // if session has no categories entry, OR if it does AND it was retrieved over X minutes ago
            if( session()->missing('categories') || ( session()->has('categories') && session('categories')['retrieved_at']->lt(now()->subMinutes(config('categories.retrieval_rate'))) ) ) {

                // get the top-level categories
                $response = MPECategories::list();

                // retrieve categories and store data to user session
                session(['categories' => [
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
