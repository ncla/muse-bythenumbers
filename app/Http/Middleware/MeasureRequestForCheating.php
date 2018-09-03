<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MeasureRequestForCheating
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $response
     */
    public function terminate($request, $response)
    {
        //Log::debug([LARAVEL_START, microtime(true), (microtime(true) - LARAVEL_START)]);
        Log::debug([$request->session()->has('last_vote_request'), $request->session()->get('last_vote_request')]);
        if ($request->session()->has('last_vote_request')) {
            $value = $request->session()->get('last_vote_request');
            Log::debug([$value, LARAVEL_START]);
        }

        $request->session()->put('last_vote_request', microtime(true));
    }
}
