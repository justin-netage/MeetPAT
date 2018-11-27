<?php

namespace MeetPAT\Http\Middleware;

use Closure;

class MeetpatClient
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
        if(\Auth::user()->client and \Auth::user()->client->active)
        {
            return $next($request);
        } else {
            return abort(404);
        }
 
    }
}
