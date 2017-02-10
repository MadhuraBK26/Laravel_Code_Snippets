<?php

namespace App\Http\Middleware;

use Closure;

class AclAuthorize
{
    /**
     * @var \App\Repositories\RoleRepository
     */
    private $roleRepository;

    /**
     * @var \App\Services\Auth\AclAuthentication
     */
    private $aclAuthentication;

    /**
     * @var \Illuminate\Routing\Router
     */
    private $router;

    public function __construct(\Illuminate\Routing\Router $router, \App\Services\Auth\AclAuthentication $aclAuthentication, \App\Repositories\RoleRepository $roleRepository)
    {
        $this->router = $router;
        $this->aclAuthentication = $aclAuthentication;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roleId = null)
    {        
        $route = $this->router->getRoutes()->match($request)->getName();
        if (!$this->aclAuthentication->isRouteAccessible($route)) {
            return response(view('errors.403'), 401);
        }
        return $next($request);
    }
}
