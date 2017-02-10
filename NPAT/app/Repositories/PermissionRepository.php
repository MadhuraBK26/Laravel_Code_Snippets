<?php

namespace App\Repositories;

use Bican\Roles\Models\Role as Role;
use Bican\Roles\Models\Permission as Permission;
use App\Models\User as User;
use Illuminate\Database\Eloquent\Model as Model;
use App\Models\PermissionBasicType;
use App\Models\PermissionGroup;

class PermissionRepository
{

    /**
     * @var App\Models\PermissionGroup
     */
    private $permissionGroup;

    /**
     * @var App\Models\PermissionBasicType
     */
    private $permissionBasicType;

    /**
     * @var PermissionRoleRepository
     */
    private $permissionRoleRepository;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var Role
     */
    private $role;

    /**
     * Injecting Role and Permission models of Bican package into this class via constructor
     * @param Role                     $role
     * @param Permission               $permission
     * @param PermissionRoleRepository $permissionRoleRepository
     */
    public function __construct(Role $role, Permission $permission, PermissionRoleRepository $permissionRoleRepository, PermissionBasicType $permissionBasicType, PermissionGroup $permissionGroup)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->permissionRoleRepository = $permissionRoleRepository;
        $this->permissionBasicType = $permissionBasicType;
        $this->permissionGroup = $permissionGroup;
    }

    /**
     * Creates the permission object
     * @param  text        $name Human readable name for permission
     * @param  type        $slug Machine readable name for permission
     * @param  $modelObject Objet of any model to which permission need to be applied
     * @return \Permission
     */
    public function createPermission($name, $slug, Model $modelObject = null)
    {
        $permissionData = [
            'name' => $name,
            'slug' => $slug,
        ];
        if ($modelObject) {
            $permissionData['model'] = get_class($modelObject);
        }
        $permission = $this->permission->create($permissionData);

        return $permission;
    }

    /**
     * Assigns a permission to user
     * @param  Permission $permission
     * @param  User       $user
     * @return User       $user
     */
    public function assignPermissionToUser(Permission $permission, User $user)
    {
        $user->attachPermission($permission);

        return $user->find($user->id);
    }

    /**
     * Assigns a permission to a role
     * @param  Permission $permission
     * @param  Role       $role
     * @param  Boolean    $isAssigned
     * @return Role
     */
    public function assignPermissionToRole(\Bican\Roles\Models\Permission $permission, Role $role, $isAssigned = false)
    {
        $role->attachPermission($permission);
        $permissionRole = $this->permissionRoleRepository->getPermissionRoleObject($permission, $role);
        $permissionRole->is_assigned = $isAssigned;
        $permissionRole->save();

        return $role->find($role->id);
    }

    public function getPermissions()
    {
        return $this->permission->all();
    }

    public function getPermissionFromPermissionSlug($permissionSlug)
    {
        return $this->permission->where("slug", $permissionSlug)->first();
    }
    
    public function getPermissionFromPermissionGroupAndTheBasicOperation($permissionGroupSlug, $permissionBasicTypeSlug)
    {
        return $this->permission          
                ->select("*","permissions.slug as slug")
                ->leftJoin('permission_groups','permission_groups.id','=','permissions.permission_group_id')
                ->leftJoin('permission_basic_types','permission_basic_types.id','=','permissions.permission_basic_type_id')
                ->where('permission_groups.slug',$permissionGroupSlug)
                ->where('permission_basic_types.slug',$permissionBasicTypeSlug)->first();
        
    }
    
    public function getPermissionFromRoute($routeName)
    {
        return $this->permission
                ->leftJoin('permission_routes','permission_routes.permission_id','=','permissions.id')
                ->where('route_name',$routeName)
                ->first();
    }
        
}
