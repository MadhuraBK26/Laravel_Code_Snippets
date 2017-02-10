<?php

namespace App\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;

class LoginThrottle
{
    /**
     * The throttle instance.
     *
     */
    protected $throttle;
    /**
     * Create a new throttle middleware instance.
     */
    public function __construct(Throttle $throttle)
    {
        $this->throttle = $throttle;
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $limit
     * @param int $time
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 10, $time = 2)
    {
        if (!$this->throttle->attempt($request, $limit, $time)) {
            return back()->with('timeout', 'You have tried more than two times,You have to wait for more than 30 minutes to Login');
        }
        return $next($request);
    }

}
