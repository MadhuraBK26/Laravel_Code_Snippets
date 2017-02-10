<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\ThrottleRepository;

class ThrottleResetPassword
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
            return back()->with('data', 'You need to contact System Administrator to Reset the password');
        }
        return $next($request);
    }
}
