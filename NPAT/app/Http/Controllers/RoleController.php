<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Auth;
use Session;

class RoleController extends Controller
{

    /**
     * @var \App\Services\Auth\AclAuthentication
     */
    private $aclAuthentication;

    /**
     * @var \App\Repositories\UserRepository
     */
    private $userRepository;

    /**
     * @var App\Repositories\PermissionGroupRepository
     */
    private $permissionGroupRepository;

    /**
     * @var \App\Repositories\PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var \App\Repositories\RoleRepository
     */
    private $roleRepository;

    public function __construct(\App\Repositories\RoleRepository $roleRepository, \App\Repositories\PermissionRepository $permissionRepository, \App\Repositories\PermissionGroupRepository $permissionGroupRepository, \App\Repositories\UserRepository $userRepository, \App\Services\Auth\AclAuthentication $aclAuthentication)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->permissionGroupRepository = $permissionGroupRepository;
        $this->userRepository = $userRepository;
        $this->aclAuthentication = $aclAuthentication;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $roles = $this->roleRepository->getRoles();
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.role.list', compact('roles', 'getRoleDetails', 'getRoleName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            "role.name" => 'required',
        ]);
        if ($validator->fails()) {
            return redirect(route('role'))->withInput()->withErrors($validator);
        }
        $allPermissions = $request->input("permission.all.permission_id");
        $assignedToPermissions = $request->input("permission.assigned_to.permission_id");
        $roleName = $request->input("role.name");

        $saveRoleAndPermission = $this->roleRepository->saveRoleAndPermissions($roleName, $allPermissions, $assignedToPermissions);
        return redirect(route('role'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $permissionGroups = $this->permissionGroupRepository->getPermissionGroups();
        return view('admin.role.create', compact('permissionGroups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $role = $this->roleRepository->getRole($id);
        $permissionGroups = $this->permissionGroupRepository->getPermissionGroups();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        return view('admin.role.add', compact('permissionGroups', 'role', 'getRoleDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = $this->roleRepository->getRole($id);
        $validator = \Validator::make($request->all(), [
            "role.name" => 'required',
        ]);
        if ($validator->fails()) {
            return redirect(route('role'))->withInput()->withErrors($validator);
        }
        $allPermissions = $request->input("permission.all.permission_id");
        $assignedToPermissions = $request->input("permission.assigned_to.permission_id");
        $roleName = $request->input("role.name");
        $this->roleRepository->updateRoleAndPermissions($role, $roleName, $allPermissions, $assignedToPermissions);
        return redirect(route('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::where('id', '=', $id)->delete();
        return redirect(route('role'));
    }

    public function indexAssignRolesToUser()
    {
        $roles = $this->roleRepository->getRoles();
        $users = $this->userRepository->getUsers();
        return view('admin.role.assignRolesToUser', compact('roles', 'users'));
    }

    public function storeAssignRolesToUser(Request $request)
    {
        $user_id = $request->get("user_id");
        $user = $this->userRepository->getUser($user_id);
        $role_ids = $request->get("role_ids");
        foreach ($role_ids as $role_id) {
            $role = $this->roleRepository->getRole($role_id);
            $this->roleRepository->assignRoleToUser($role, $user);
        }
        return redirect(route('admin.role.index_assign_roles_to_user'));
    }
}
