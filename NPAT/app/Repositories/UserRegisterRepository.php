<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use App\Models\User;
use App\Models\PracticesUser;
use Auth;
use DB;
use Session;
use Config;

use App\Repositories\UtilityRepository;
use App\Models\NavigatorCurrentCompany;
use App\Models\NavigatorExperience;
use App\Models\NavigatorPersonal;
use App\Models\NavigatorPerformance;

class UserRegisterRepository extends LaravelJqgridRepositoryService
{

    public $validationRules = [
        'name' => 'required|regex:/(^[A-Za-z\s]+$)+/',
        'emp_id' => 'required|regex:/(^[A-Za-z0-9]+$)+/',
        'email' => 'required|email',
        'password' => 'confirmed|min:3',
        'password_confirmation' => 'min:3',
    ];

    public $validationRulesadd=[
        'name' => 'required|regex:/(^[A-Za-z\s]+$)+/',
        'emp_id' => 'required|regex:/(^[A-Za-z0-9]+$)+/',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
        'password_confirmation' => 'min:6',
    ];
    protected $userRepository;

    public function __construct(UtilityRepository $utilityRepository,\App\Services\Auth\AclAuthentication $aclAuthentication, UserRepository $userRepository)
    {
        $this->utilityRepository = $utilityRepository;
        $aclAuthentication->can('list-of-users');
        $userRoles = Auth::user();
        $userId = Auth::user()->id;
        $getHierarchicalId = $userRepository->getReportersInHeirarchy($userId);
        $this->Database = DB::table('users')
            ->select('users.*', DB::raw("(CASE WHEN (users.status = 1) THEN 'Active' ELSE 'InActive' END) as status"), DB::raw('GROUP_CONCAT(DISTINCT roles.name) as role_name'), DB::raw('GROUP_CONCAT(DISTINCT roles.id) as role_id'), 'navigator_designations.name as navigator_name', 'reporting_manager.name as reporting_manager_name', DB::raw('GROUP_CONCAT(DISTINCT practices.id) as practices_id'), DB::raw('GROUP_CONCAT(DISTINCT practices.practices) as practices_name'),DB::Raw("(CASE WHEN (users.is_manager = 1) THEN 'Yes' ELSE 'No' END) as is_manager"),DB::Raw('GROUP_CONCAT(DISTINCT users.is_manager) as is_manager_cond'))
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftjoin('practices_user', 'practices_user.user_id', '=', 'users.id')
            ->leftjoin('practices', 'practices.id', '=', 'practices_user.practices_id');

        if ((Auth::user()->role_id != config('custom.adminId')) && (Session::get('role') != config('custom.projectManagerLead')) && Session::get('role') != config('custom.DeliveryHead')) {
            $this->Database->orWhereIn('users.id', $getHierarchicalId);
        }
        if (Auth::user()->role_id == config('custom.DeliveryHead')) {
            $this->Database->where('users.reporting_manager_id', $userRoles['id']);
        }
        if (Auth::user()->role_id == config('custom.projectManagerLead')) {
            $this->Database->where('users.id', '!=', $userId)
                ->where('users.deleted_at', '=', null);
        }
        $this->Database->join('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id')
            ->leftjoin('users as reporting_manager', 'reporting_manager.id', '=', 'users.reporting_manager_id')
            ->groupBy('users.id')
            ->where('users.deleted_at', '=', null);
        $this->visibleColumns = array('id', 'emp_id', 'name', 'role_id', 'navigator_designation_id', 'reporting_manager_id', 'email', 'practices_id');
        $this->orderBy = array(array('id', 'desc'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function storeUserDetails($userName, $userEmpId, $userEmail, $userPassword, $userDesignation, $userReportingManager, $userPractices,$userIsManager)
    {
        $user = new User();
        $user->name = $userName;
        $user->emp_id = $userEmpId;
        $user->email = $userEmail;
        $user->password = $userPassword;
        $user->navigator_designation_id = $userDesignation;
        $user->reporting_manager_id = $userReportingManager;
        $user->is_manager= $userIsManager === "Yes" ? 1 : 0;
        try {
            $user->save();
            // Update practices
            $this->practicesUser($user->id, $userPractices);
            return $user;

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
    }

    /**
     * Update created user in storage.
     *
     * @param  Request $request
     * @return Response
     */

    public function updateUserDetails($id, $userName, $userEmpId, $userEmail, $userPassword, $userDesignation, $userReportingManager, $userPractices,$userIsManager)
    {
        $user = User::find($id);
        $user->name = $userName;
        $user->emp_id = $userEmpId;
        $user->email = $userEmail;
        if($userPassword){
            $user->password = $userPassword;
        }
        $user->navigator_designation_id = $userDesignation;
        $user->reporting_manager_id = $userReportingManager;
        $user->is_manager= $userIsManager === "Yes" ? 1 : 0;
        $user->save();
        $this->practicesUser($user->id, $userPractices);
        return $user;
    }


    /**
     * Inserting and Updating PracticesUser Pivot table
     *
     * @param $userName
     * @param $userPractices
     * @return bool
     */
    public function practicesUser($userid, $userPractices)
    {
        PracticesUser::where('user_id','=',$userid)->delete();
        if (!is_array($userPractices)) {
            $userPractices = (array)$userPractices;
        }
        foreach ($userPractices as $key => $practiceValue) {
            $adduserPractices = new PracticesUser();
            $adduserPractices->user_id = $userid;
            $adduserPractices->practices_id = $practiceValue;
            $adduserPractices->save();
        }
        return true;
    }

    /**
     *@param array or object
     *return boolean true or false
    */
    public function updateUserCurrentCompanyDetails($request)
    {
        ($request['date_of_join'])?
            $date_of_join = $this->utilityRepository->getDateFormat('Y-m-d', $request['date_of_join'])
        :$date_of_join = null;

        ($request['last_working_day'])?
            $last_working_day = $this->utilityRepository->getDateFormat('Y-m-d', $request['last_working_day'])
        :$last_working_day = null;

        $userData =[
            'user_id'                => $request['user_id'],
            'date_of_join'           => $date_of_join,
            'last_working_day'       => $last_working_day,
            'department_id'          => $request['department_id'],
            'division_head_id'       => $request['practice_head_id'],
            'probation_confirmation' => $request['probation_confirmation'],
            'ctc'                    => $request['current_ctc']
        ];        
        // print_r($userData);exit;
        // store practices
        User::where('id',$request['user_id'])->update(['status' => $request['emp_status']]);
        
        $user = NavigatorCurrentCompany::where('user_id',$request['user_id'])->first();
        if($user){
            return $dbRes = NavigatorCurrentCompany::where('id',$user->id)->update($userData);
        }

        return $dbRes = NavigatorCurrentCompany::create($userData);
    }

    /**
     *@param array or object
     *return boolean true or false
    */
    public function updateUserExperienceDetails($request)
    {
        $userData =[
            'user_id'                   => $request['user_id'],
            'relevent_exp'              => $request['relevant_experience'],
            'total_exp'                 => $request['total_experience'],
            'organisation_exp'           => $request['exp_in_current_org'],
            'previous_company_name'     => $request['previous_company_name'],
            'previous_designation'      => $request['designation'],
            'previous_ctc'              => $request['previous_ctc']
        ];

        $user = NavigatorExperience::where('user_id',$request['user_id'])->first();
        if($user){
            return $dbRes = NavigatorExperience::where('id',$user->id)->update($userData);
        }

        return $dbRes = NavigatorExperience::create($userData);
    }

    /**
     *@param array or object
     *return boolean true or false
    */
    public function updateUserPersonalDetails($request)
    {   
        // echo $request['date_of_birth'],'------------';
        ($request['date_of_birth'])?
            $dob = $this->utilityRepository->getDateFormat('Y-m-d', $request['date_of_birth'])
        :$dob = null;
        // exit;
        $userData =[
            'user_id'               => $request['user_id'],
            'father_name'           => $request['father_name'],
            'marital_status'        => $request['marital_status'],
            'date_of_birth'         => $dob,
            'residential_address'   => $request['residential_address'],
            'present_address'       => $request['present_address'],
            'mobile_number'         => $request['contact_number'],
            'landline'              => $request['landline'],
            'personal_email'        => $request['personal_email'],
            'pan_number'            => $request['pan_number'],
            'aadhar_number'         => $request['aadhar_number']
        ];

        $user = NavigatorPersonal::where('user_id',$request['user_id'])->first();
        if($user){
            return $dbRes = NavigatorPersonal::where('id',$user->id)->update($userData);
        }

        return $dbRes = NavigatorPersonal::create($userData);
    }

    /**
     *@param array or object
     *return boolean true or false
    */
    public function updateUserPerformanceDetails($request)
    {
        $userData1 =[
            'user_id'        => $request['user_id'],
            'interim_hike'   => $request['intrim_hike_1'],
            'rating'       => $request['rating_1'],
            'promotion'     => $request['promotion_1'],
            'compensation'  => $request['compensation_1'],
        ];

        $userData2 =[
            'user_id'        => $request['user_id'],
            'interim_hike'   => $request['intrim_hike_2'],
            'rating'       => $request['rating_2'],
            'promotion'     => $request['promotion_2'],
            'compensation'  => $request['compensation_2']
        ];

        $this->updatePerformances($userData1, $userData2, $request['user_id']);        
        
    }

    function updatePerformances($userData1, $userData2, $user_id){
        $entry_count = 1;
        $user_entries = NavigatorPerformance::where('user_id',$user_id)->get();
        if(count($user_entries) >=1 ){
            foreach($user_entries as $entry){
                $arrayname = 'userData'.$entry_count;
                $dbRes = NavigatorPerformance::where('id',$entry->id)->update($$arrayname);
                $entry_count++;
            }

        }else{
            $dbRes = NavigatorPerformance::create($userData1);
            $dbRes = NavigatorPerformance::create($userData2);
        }
    }

    /**
     *@param array or object
     *return boolean true or false
    */
    public function getUserAdvancedDetails($id)
    {
        $user = [];
        $basicData = User::where('id',$id)->first();
        if($basicData){
            $user['basicData'] = $basicData;
        }

        $user['basicData']['practices_id'] = PracticesUser::where('user_id',$id)->pluck('practices_id');

        $companyData = NavigatorCurrentCompany::where('user_id',$id)->first();
        if($companyData){
            $user['companyData'] = $companyData;
        }

        $expData = NavigatorExperience::where('user_id',$id)->first();
        if($expData){
            $user['expData'] = $expData;
        }

        $personalData = NavigatorPersonal::where('user_id',$id)->first();
        if($personalData){
            $user['personalData'] = $personalData;
        }

        $performanceData = NavigatorPerformance::where('user_id',$id)->get();
        if($performanceData){
            $user['performanceData'] = $performanceData;
        }

        return $user;
    }
}