<?php

namespace App\Repositories;

class PermissionGroupRepository
{

    /**
     * @var \App\Models\PermissionGroup
     */
    private $permissionGroup;

    public function __construct(\App\Models\PermissionGroup $permissionGroup)
    {
        $this->permissionGroup = $permissionGroup;
    }
    
    public function getPermissionGroups()
    {
        return $this->permissionGroup->with('permissions')->get();
    }
}
