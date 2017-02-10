<?php

namespace App\Repositories;

use DB;
use Bican\Roles\Models\Permission;
use App\Models\RoleUser;
use App\Models\User;

class RoleUserRepository
{
    protected $roleUserRepository;

    public function getPermissionBasedOnUserRole(User $user, Permission $permission = null, $roleId)
    {         
        $q = DB::table('role_user')
                ->leftJoin('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')
                ->leftJoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->where('user_id', $user->id);
            if($roleId){
                $q ->where('permission_role.role_id', $roleId);
            }
        if($permission)       {
            $q->where('permissions.id',$permission->id);
        }
       return $q->first();
    }

    public function storeRoleUserDetails($roleUserId)
    {
        $roleUser = new RoleUser();
        $roleUser->role_id = $roleUserId;
        try {
            $roleUser->save();
            return $roleUser;

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'User created successfully !'));
    }

    public function updateRoleUserDetails($id, $roleUser)
    {
        $roleId = RoleUser::find($id);
        $roleId->role_id = $roleUser;
        try {
            $roleId->save();
            return $roleId;

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'User created successfully !'));
    }

}
