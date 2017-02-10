<?php

namespace App\Repositories;

class PermissionRoleRepository
{
    /**
     * @var \App\Models\PermissionRole
     */
    private $permissionRole;

    public function __construct(\App\Models\PermissionRole $permissionRole)
    {
        $this->permissionRole = $permissionRole;
    }
    public function getPermissionRoleObject(\Bican\Roles\Models\Permission $permission, \Bican\Roles\Models\Role $role)
    {
        return $this->permissionRole->where("permission_id", "=", $permission->id)
            ->where('role_id', "=", $role->id)->first();
    }
}
