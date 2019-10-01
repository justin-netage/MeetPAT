<?php

namespace MeetPAT\Http\Middleware;

use Closure;

class Reseller
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
        if(\Auth::user()->reseller and \Auth::user()->reseller->active)
        {
            return $next($request);
        } else {
            return abort(401);
        }
    }
}
