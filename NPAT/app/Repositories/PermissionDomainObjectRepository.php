<?php

namespace App\Repositories;

use App\Repositories\DomainObjectRepository as DomainObjectRepository;
use App\Models\PermissionDomainObject as PermissionDomainObject;
use App\Services\ReflectionClassService;

class PermissionDomainObjectRepository
{

    /**
     * @var ReflectionClassService
     */
    private $reflectionClassService;

    /**
     * @var PermissionDomainObject
     */
    protected $permissionDomainObject;
    protected $domainObjectRepository;

    public function __construct(PermissionDomainObject $permissionDomainObject, DomainObjectRepository $domainObjectRepository, ReflectionClassService $reflectionClassService)
    {
        $this->domainObjectRepository = $domainObjectRepository;
        $this->permissionDomainObject = $permissionDomainObject;
        $this->reflectionClassService = $reflectionClassService;
    }

    public function assignUserThePermissionOfDomainObject($user, $permission, $modelObject)
    {
        $modelName = $this->reflectionClassService->getClassName($modelObject);
        $modelPrimaryKeyValue = $this->reflectionClassService->getPrimaryKeyValue($modelObject);
        $domainObject = $this->domainObjectRepository->getOrCreateDomainObject($modelName, $modelPrimaryKeyValue);
        return $this->getOrCreatePermissionDomainObject($user, $permission, $domainObject);
    }

    public function createPermissionDomainObject($user, $permission, $domainObject)
    {
        $permissionDomainObject = new PermissionDomainObject();
        $permissionDomainObject->user_id = $user->id;
        $permissionDomainObject->permission_id = $permission->id;
        $permissionDomainObject->domain_object_id = $domainObject->id;
        $permissionDomainObject->save();
        return $permissionDomainObject;
    }

    public function getPermissionDomainObject($user, $permission, $domainObject)
    {
        return $this->permissionDomainObject->where("user_id", "=", $user->id)
                        ->where("permission_id", "=", $permission->id)
                        ->where("domain_object_id", "=", $domainObject->id)
                        ->first();
    }

    public function getOrCreatePermissionDomainObject($user, $permission, $domainObject)
    {
        $permissionDomainObject = $this->getPermissionDomainObject($user, $permission, $domainObject);
        if (!$permissionDomainObject) {
            $permissionDomainObject = $this->createPermissionDomainObject($user, $permission, $domainObject);
        }
        return $permissionDomainObject;
    }

}
