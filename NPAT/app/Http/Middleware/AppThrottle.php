<?php

namespace App\Http\Middleware;

use Closure;
use GrahamCampbell\Throttle\Throttle;

class AppThrottle
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
    public function handle($request, Closure $next, $limit = 10, $time = 60)
    {
        if (!$this->throttle->attempt($request, $limit, $time)) {
            return back()->with('data', 'You have tried more than three times,Please contact System Administrator to reset the password');
        }
        
        return $next($request);
    }
}
