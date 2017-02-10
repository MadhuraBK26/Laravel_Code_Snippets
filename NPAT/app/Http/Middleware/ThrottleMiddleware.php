<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\ThrottleRepository;


class ThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$limit,$time)
    {
        $a = new ThrottleRepository();

        if($a->createOrUpdate("email", $request->get('email'), $limit, $time))
        {
            return back()->with('timeout', 'You have tried more than two times,You have to wait for more than 30 minutes to Login');
        }
        return $next($request);
    }
}
