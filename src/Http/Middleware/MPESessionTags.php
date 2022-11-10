<?php

namespace Code23\MarketplaceLaravelSDK\Http\Middleware;

use Closure;
use Code23\MarketplaceLaravelSDK\Facades\MPETags;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESessionTags
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
            // if session has no tags entry, OR if it does AND it was retrieved over X minutes ago
            if( session()->missing('tags') || ( session()->has('tags') && session('tags')['retrieved_at']->lt(now()->subMinutes(config('tags.retrieval_rate'))) ) ) {

                // get the top-level tags
                $response = MPETags::list();

                // retrieve tags and store data to user session
                session(['tags' => [
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
