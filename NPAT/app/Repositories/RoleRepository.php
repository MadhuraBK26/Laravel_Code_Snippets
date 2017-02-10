<?php

namespace App\Repositories;

use Bican\Roles\Models\Role as Role;
use Bican\Roles\Models\Permission as Permission;
use App\Services\LaravelJqgridRepositoryService;
use Illuminate\Support\Facades\DB;

class RoleRepository extends LaravelJqgridRepositoryService
{
    /**
     * @var \App\Repositories\PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var Role
     */
    private $role;

    /**
     * Constructor for repository to access all the methods in repository
     */
    public function __construct(Role $role, Permission $permission, \App\Repositories\PermissionRepository $permissionRepository)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->permissionRepository = $permissionRepository;
        $this->Database = DB::table('roles')
                ->join('permission_role', 'permission_role.role_id', '=', 'roles.id')
                ->select('roles.*', DB::raw('GROUP_CONCAT(permission_role.permission_id) as permission_role_id'))
                ->groupBy('role_id');

        $this->visibleColumns = array('id', 'name', 'level', 'permission_role_id');
        $this->orderBy = array(array('id', 'asc'));
    }

    public function createRole($name, $slug)
    {
        $role = $this->role->create([
            'name' => $name,
            'slug' => $slug,
        ]);

        return $role;
    }

    public function assignRoleToUser($role, $user)
    {
        $user->roles()->attach($role->id);

        return $user->find($user->id);
    }

    public function getRoles()
    {
        return $this->role->all();
    }

    public function saveRoleAndPermissions($name, $allPermissions, $assignedPermissions)
    {
        $role = $this->createRole($name, str_slug($name));
        if ($allPermissions && is_array($allPermissions)) {
            foreach ($allPermissions as $allPermission) {
                $permission = $this->permission->find($allPermission);
                $this->permissionRepository->assignPermissionToRole($permission, $role, false);
            }
        }
        if ($assignedPermissions && is_array($assignedPermissions)) {
            foreach ($assignedPermissions as $assignedPermission) {
                $permission = $this->permission->find($assignedPermission);
                $this->permissionRepository->assignPermissionToRole($permission, $role, true);
            }
        }
        return $role;
        if ($role || $permission) {
            return json_encode(array('success' => true, 'message' => 'Roles created successfully !'));
        }

        return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
    }

    public function updateRoleAndPermissions($role, $name, $allPermissions, $assignedPermissions)
    {
        $role->name = $name;
        $role->save();
        $role->permissions()->detach();
        if ($allPermissions && is_array($allPermissions)) {
            foreach ($allPermissions as $allPermission) {
                $permission = $this->permission->find($allPermission);
                $this->permissionRepository->assignPermissionToRole($permission, $role, false);
            }
        }

        if ($assignedPermissions && is_array($assignedPermissions)) {
            foreach ($assignedPermissions as $assignedPermission) {
                $permission = $this->permission->find($assignedPermission);
                $this->permissionRepository->assignPermissionToRole($permission, $role, true);
            }
        }

        return $role;
    }



    public function getRole($id)
    {
        $roleuser = Role::find($id);

        return $roleuser;
    }
    
    public function getIsAssignedForPermission($role,$permission)
    {
        $role_id = $role->id;
        $permission_id = $permission->id;
        $permission_role = DB::table('permission_role')->where('permission_id',$permission_id)
                ->where('role_id',$role_id)->first();
        if($permission_role){
            return (int)$permission_role->is_assigned;
        }
    }
    
}
