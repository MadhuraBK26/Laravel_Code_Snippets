<?php

namespace App\Services;

use App\Repositories\RoleUserRepository;
use App\Repositories\UserRegisterRepository;
use App\Repositories\RoleRepository;
use App\Models\User;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRegisterRepository;
    private $roleUserRepository;
    private $roleRepository;

    public function __construct(UserRegisterRepository $userRegisterRepository,
                                RoleUserRepository $roleUserRepository,
                                RoleRepository $roleRepository)
    {
        $this->userRegisterRepository = $userRegisterRepository;
        $this->roleUserRepository = $roleUserRepository;
        $this->roleRepository = $roleRepository;
    }


    /**
     * @param $userName ,$userEmpId,$userEmail,$userPassword
     * @param $userRole ,$userDesignation,$userReportingManager,$userPractices,$userIsManager
     * @return \App\Repositories\Response|string
     */
    public function storeUserAndRoleDetails($userName, $userEmpId, $userEmail, $userPassword, $userRole, $userDesignation, $userReportingManager, $userPractices, $userIsManager)
    {
        $userExistOrNot = User::select('name', 'emp_id', 'email')
            ->where('name', $userName)
            ->where('emp_id', $userEmpId)
            ->first();
        if (!$userExistOrNot) {
            $user = $this->userRegisterRepository->storeUserDetails($userName, $userEmpId, $userEmail, $userPassword, $userDesignation, $userReportingManager, $userPractices, $userIsManager);
            if (is_array($userRole)) {
                foreach ($userRole as $userRoleId) {
                    $roleAssignToUser = $this->roleRepository->assignRoleToUser($this->roleRepository->getRole($userRoleId), $user);
                }
            } else {
                $roleAssignToUser = $this->roleRepository->assignRoleToUser($this->roleRepository->getRole($userRole), $user);
            }
            if ($user) {
                return json_encode(array('success' => true, 'message' => 'User created successfully !'));
            }
            return $user;
        } else {
            return json_encode(array('success' => false, 'message' => 'User Already Exists.'));
        }
    }


    /**
     * @param $id ,$userName,$userEmpId,$userEmail,$userPassword
     * @param $userRole ,$userDesignation,$userReportingManager,$userPractices,$userIsManager
     * @return \App\Repositories\Response|string
     */
    public function updateUserAndRoleDetails($id, $userName, $userEmpId, $userEmail, $userPassword, $userRole, $userDesignation, $userReportingManager, $userPractices, $userIsManager)
    {
        $user = $this->userRegisterRepository->updateUserDetails($id, $userName, $userEmpId, $userEmail, $userPassword, $userDesignation, $userReportingManager, $userPractices, $userIsManager);
        if (is_array($userRole)) {

            $user->roles()->sync($userRole);

        } else {
            $user->roles()->sync(array($userRole));
        }
        if ($user) {
            return json_encode(array('success' => true, 'message' => 'User Record updated successfully !'));
        } else {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return $user;
    }

    public function getUserPersonalDetails($emp_id)
    {
        $user = User::where('emp_id','=',$emp_id)->first();
        if(isset($user->id))
        {
            $userData = $this->userRegisterRepository->getUserAdvancedDetails($user->id);
            return $userData;
        }
        return false;
    }

    /**
     *@param array $request contains multiple fields
     *@return boolean true or false
    */
    public function updateUserPersonalDetails($request)
    {
        try{
            $user = User::where('id',$request['user_id'])->first();
            if($user->id)
            {   
                $this->userRegisterRepository->updateUserCurrentCompanyDetails($request);
                $this->userRegisterRepository->updateUserExperienceDetails($request);
                $this->userRegisterRepository->updateUserPersonalDetails($request);
                $this->userRegisterRepository->updateUserPerformanceDetails($request);
                return array('alert' => 'success', 'message' => 'User Record updated successfully !');
            }
            return array('alert' => 'error', 'message' => 'Something wrong with the user data, please try again later.');

        }catch(\Exception $e){
            return array('alert' => 'error', 'message' => 'Something went wrong, please try again later.');
        }
    }
}
