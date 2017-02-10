<?php

namespace App\Services\Auth;

use App\Repositories\PermissionRepository as PermissionRepository;
use App\Models\User as User;
use Session;
use App\Repositories\PermissionDomainObjectRepository as PermissionDomainObjectRepository;
use App\Services\ReflectionClassService;
use App\Repositories\RoleUserRepository;
use App\Repositories\DomainObjectRepository;
use Illuminate\Http\Request;

/**
 * Description of AclAuthentication
 *
 * @author jeevan
 */
class AclAuthentication
{
    /**
     * @var DomainObjectRepository
     */
    private $domainObjectRepository;

    /**
     * @var RoleUserRepository
     */
    private $roleUserRepository;

    /**
     * @var ReflectionClassService
     */
    private $reflectionClassService;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var App\Repositories\PermissionDomainObjectRepository
     */
    protected $permissionDomainObjectRepository;

    /**
     * @var User
     */
    protected $user;

    protected $request;
    
    public $isPermissionAssignedTo = null;

    public function __construct(PermissionRepository $permissionRepository, User $user, PermissionDomainObjectRepository $permissionDomainObjectRepository, ReflectionClassService $reflectionClassService, RoleUserRepository $roleUserRepository, DomainObjectRepository $domainObjectRepository, Request $request)
    {
        $this->permissionRepository = $permissionRepository;
        $this->user = $user;
        $this->permissionDomainObjectRepository = $permissionDomainObjectRepository;
        $this->reflectionClassService = $reflectionClassService;
        $this->roleUserRepository = $roleUserRepository;
        $this->domainObjectRepository = $domainObjectRepository;
        $this->request = $request;
    }

    public function can($permissionSlug = null, $domainObject = null)
    {
        $this->isPermissionAssignedTo = null;
        if ($this->isSecurityBypassRequired()) {
            return true;
        }
        $isAuthorized = false;
        $user = $this->getAuthenticatedUser();
        if ($user && $permissionSlug) {
            $permission = $this->permissionRepository->getPermissionFromPermissionSlug($permissionSlug);
            $roleUser = $this->roleUserRepository->getPermissionBasedOnUserRole($user, $permission, Session::get('role'));

            if($roleUser){

            $this->isPermissionAssignedTo = $roleUser->is_assigned;
            
        }
            if ($roleUser && $roleUser->is_assigned == 1 && $domainObject) {
                $modelName = $this->reflectionClassService->getClassName($domainObject);
                $modelPrimaryKeyValue = $this->reflectionClassService->getPrimaryKeyValue($domainObject);
                $domainObjectIdentity = $this->domainObjectRepository->getOrCreateDomainObject($modelName, $modelPrimaryKeyValue);
                $isAuthorized = $this->permissionDomainObjectRepository->getPermissionDomainObject($user, $permission, $domainObjectIdentity) ? true : false;
            } 
            elseif ($roleUser && ($roleUser->is_assigned == 0 || $roleUser->is_assigned == 1)) {
                $isAuthorized = true;
            }
        } else {
            $isAuthorized = $user ? true : false;
        }

        return $isAuthorized;
    }
    public function isPermissionAssignedTo()
    {
        
        return $this->isPermissionAssignedTo;
    }
    public function canCrud($permissionGroupSlug, $permissionBasicTypeSlug, $domainObject = null)
    {
        if ($this->isSecurityBypassRequired()) {
            return true;
        }
        $permission = $this->permissionRepository->getPermissionFromPermissionGroupAndTheBasicOperation($permissionGroupSlug, $permissionBasicTypeSlug);
        if ($permission) {
            return $this->can($permission->slug, $domainObject);
        }

        return false;
    }

    public function is($roleSlug = null)
    {
        if ($this->isSecurityBypassRequired()) {
            return true;
        }
        $isAuthorized = false;
        if ($this->getAuthenticatedUser()) {
            $isAuthorized = true;
            if ($roleSlug) {
                $isAuthorized = $this->authenticatedUser->is($roleSlug);
            }
        }

        return $isAuthorized;
    }

    public function getAuthenticatedUser()
    {
        $this->authManager = app('auth');
        $this->authenticatedUser = null;
        if ($this->authManager->check()) {
            $this->authenticatedUser = $this->user->find($this->authManager->user()->id);
        }

        return $this->authenticatedUser;
    }

    public function authenticate($user)
    {
        $this->authManager = app('auth');
        $this->authManager->login($user);
    }

    public function logout()
    {
        $this->authManager = app('auth');
        $this->authManager->logout();
    }

    /**
     * @param  User                                $user
     * @param  \Bican\Roles\Models\Permission      $permission
     * @param  \Illuminate\Database\Eloquent\Model $domainObject
     * @return \App\Models\PermissionDomainObject
     */
    public function assignPermission(User $user = null, \Bican\Roles\Models\Permission $permission = null, \Illuminate\Database\Eloquent\Model $domainObject = null)
    {
        $userWithPermission = $this->permissionRepository->assignPermissionToUser($permission, $user);
        if ($domainObject) {
            $this->permissionDomainObjectRepository->assignUserThePermissionOfDomainObject($userWithPermission, $permission, $domainObject);
        }
    }

    public function assign($permissionSlug = null, $domainObject = null, User $user = null)
    {
        $permission = $this->permissionRepository->getPermissionFromPermissionSlug($permissionSlug);
        $this->assignPermission($user, $permission, $domainObject);
    }

    public function isSecurityBypassRequired()
    {
        if ($user = $this->getAuthenticatedUser()) {
            return $user->is_super_admin == 1 ? true : false;
        }

        return false;
    }

    public function isRouteAccessible($routeName)
    {
        $permission = $this->permissionRepository->getPermissionFromRoute($routeName);
        if ($permission) {
            return $this->can($permission->slug);
        }
        return true;
    }
}
