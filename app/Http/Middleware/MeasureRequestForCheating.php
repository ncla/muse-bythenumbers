<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if (Request::input('voted_on') !== null) {
            if ($request->session()->has('last_vote_request')) {
                $oldTime = $request->session()->get('last_vote_request');

                $diff = round(LARAVEL_START - $oldTime, 3);

                if ($diff < 1) {
                    $clientDiff = null;

                    if ($request->input('time') && $request->input('time_last_response')) {
                        $clientDiff = round(( floatval($request->input('time')) - floatval($request->input('time_last_response')) ) / 1000, 3);
                    }

                    DB::table('fast_voters')
                        ->insert([
                            'time_between_votes' => $diff,
                            'time_between_votes_client' => $clientDiff,
                            'browser_useragent' => $request->userAgent(),
                            'user_id' => Auth::id(),
                            'ip_address' => $request->getClientIp()
                        ]);
                }

            }

            $request->session()->put('last_vote_request', microtime(true));
            Session::save();
        }
    }
}
