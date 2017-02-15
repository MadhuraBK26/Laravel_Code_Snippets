<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use App\Models\FeedbackTransaction;
use App\Models\User;
use App\Repositories\AdminRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;
use App\Repositories\UtilityRepository;
use App\Models\Role;
use App\Services\EncryptionService;
use App\Models\PeopleFeedback;
use Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;
use Response;
use Hash;
use Log;
use App\Services\FeedbackTransactionService;
use App\Models\PracticesUser;
use App\Models\Practices;
use App\Models\NavigatorDesignation;

class HomeController extends Controller
{

    /**
     * @var FeedbackTransactionService
     */
    private $feedbackTransactionService;
    private $encryptionService;

    /**
     * To access user and feedback functions.
     */
    protected $userRepository;
    protected $feedbackRepository;

    /**
     * Constructor for repository
     */
    public function __construct(UserRepository $userRepository, FeedbackRepository $feedbackRepository, UtilityRepository $utilityRepository, AdminRepository $adminRepo,
                                ReportRepository $reportRepo,
                                EncryptionService $encryptionService,
                                FeedbackTransactionService $feedbackTransactionService
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->utilityRepository = $utilityRepository;
        $this->adminRepo = $adminRepo;
        $this->reportRepo = $reportRepo;
        $this->encryptionService = $encryptionService;
        $this->feedbackTransactionService = $feedbackTransactionService;
    }

    public function getMyDashboard()
    {
        return view('auth.login');
    }

    /**
     * Show the application registration form.
     * @return response
     */
    public function getRegister()
    {
        $roles = $this->userRepository->getAllRoles();
        $userDetails = $this->userRepository->getRolesNameId();
        $designations = $this->userRepository->getAllDesignation();

        return view('auth.register')->with(
            [
                'roles' => $roles,
                'designations' => $designations,
                'userDetails' => $userDetails,
            ]
        );

    }

    /**
     * Show the application login form.
     * @return response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     * @param request
     * @return response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request,
            ['email' => 'required|email', 'password' => 'required']);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $userDetails = $this->userRepository->getRolesNameId();
            $getRoleDetails = $this->userRepository->getRoleIdDetails($userDetails);

            if (count($getRoleDetails) > 1) {
                return view('loginDefaultDashboard', compact('getRoleDetails'));
            } else {
                $rolId = $getRoleDetails[0]['id'];
                Session::put('role', $rolId);
                return redirect('/');
            }
        }
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function getRoleId(Request $request)
    {

        $getroleid = $this->userRepository->getRolesId();
        Session::put('role', $getroleid);
        $currentSessionRole = Session::get('role');
        if ($currentSessionRole) {
            return redirect()->route('dashboard');
        }
    }

    public function multipleLogin()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        return view('loginDefaultDashboard')
            ->with('getRoleDetails', $getRoleDetails);
    }

    /**
     * Display Password change View
     * @return mixed
     */
    public function profileDisplay()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        $reportingManager = User::select('users.id', 'users.name')
            ->where('users.id', Auth::user()->reporting_manager_id)->first();
        $userDesignation = $this->userRepository->getUserDesignation($user);
        $userPractices = $this->userRepository->getUserPractice();
        $roleId = Auth::user()->role_id;
        return view('auth.profile')
            ->with('user', $user)
            ->with('userPractices', $userPractices)
            ->with('roleId', $roleId)
            ->with('userDesignation', $userDesignation)
            ->with('reportingManager', $reportingManager)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName)
            ->with('data', route('profile-display'));
    }

    /**
     * Update User Details
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfileUpdate()
    {
           $userData = array(
            'name' => Input::get('name'),
            'emp_id' => Input::get('emp_id'),
            'email' => Input::get('email'),
        );
        if ($this->userRepository->profileUpdate($userData)) {
            return response()->json(['result' => true, 'msg' => 'Profile Updated Successfully']);
        }
    }

    /**
     * Stores User password changes
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileDetailsStore(Request $request)
    {
        $password = Input::get('current_password');
        $recovery = Input::get('recovery_question');
        if ($recovery) {
            if (Hash::check($password, Auth::user()->password)) {

                $validator = Validator::make($request->all(), ['password' => 'required|min:3|confirmed',
                    'password_confirmation' => 'required|min:3',
                ]);

                if ($validator->fails()) {
                    return response()->json(['result' => false, 'msg' => 'Password Doesnot Match'], 422);
                }

                $userdata = array(
                    'password' => Hash::make(Input::get('password')),
                    'recovery_question' => Input::get('recovery_question')
                );
                if ($this->userRepository->profileUpdate($userdata)) {
                    return response()->json(['result' => true, 'msg' => 'Password Updated Successfully']);
                }
            }
        } else {
            return response()->json(['result' => false, 'msg' => 'Please Answer Recovery Question...!'], 422);
        }

        return response()->json(['result' => false, 'msg' => 'Current Password Doesnot Match'], 422);

    }

    /**
     * Directs to Password changes View
     * @return $this
     */
    public function accountChanges()
    {
        $roleId = Auth::user()->role_id;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        return view('auth.profile_password')
            ->with('roleId', $roleId)
            ->with('getRoleDetails', $getRoleDetails);
    }

    /**
     * Directs to User Profile
     * @return mixed
     */
    public function profileUser()
    {
        $emailId = Auth::user()->email;
        $name = Auth::user()->name;
        $roleId = Auth::user()->role_id;
        return view('auth.profile_user')
            ->with('emailId', $emailId)
            ->with('name', $name)
            ->with('roleId', $roleId);
    }

    /**
     * Function checks the roles for registered users
     * @param Request $request
     * @return response
     * It redirects to Practice lead dashboard
     */
    public function getDashboard(Request $request)
    {
        if ($request->has('role')) {
            $roleIdValue = Input::get('role');
            Session::put('role', $roleIdValue);

        }
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        if ($currentSessionRole) {
            return view('admin.project.index')
                ->with('currentSessionRole', $currentSessionRole)
                ->with('getRoleDetails', $getRoleDetails)
                ->with('getRoleName', $getRoleName);
        }
    }

    /**
     * Get user report details
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function getReportDetailsDashboard()
    {
        $selected_data = Input::get('selected-data');
        if($selected_data == 'projects'){
            $projectId = Input::get('project')?Input::get('project'):0;
            $practicesId = 0;
        }else{
            $practicesId = Input::get('project')?Input::get('project'):0;
            $projectId = 0;
        }
        if(!$selected_data){
            $practicesId = Input::get('practiceName')?Input::get('practiceName'):0;
        }
        $peopleId = Input::get('people')?Input::get('people'):0;
        $fromdate = Input::get('start')?Input::get('start'):0;
        $todate = Input::get('end')?Input::get('end'):0;

        if ($projectId || $projectId == 0 || $peopleId || ($fromdate && $todate)) {
            $userId = Auth::user()->id;
            $roleId = Auth::user()->role_id;
            $userDetails = $this->userRepository->getRolesNameId();
            $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId);   
            $metricValues = $this->userRepository->getMetrics();
            $projectResources = $this->userRepository->getUserDetailsBasedOnDate($projectId, $fromdate, $todate,
                $peopleId, $practicesId, $getHierarchicalIds);
            $resourceRatings = $this->userRepository->getResourceRatings($projectResources);
            $arr = $this->userRepository->formPeopleDetails($projectResources);
            foreach ($arr as $i => $value) {

                $arr[$i]['encPeopleId'] = $this->encryptionService->encryptUrlParameter($value['peopleId']);
                $arr[$i]['encProjectId'] = $this->encryptionService->encryptUrlParameter($value['projectId']);
                $arr[$i]['encManagerId'] = $this->encryptionService->encryptUrlParameter($value['managerId']);
            }
            if ($projectResources) {
                return view('practiceLeadReport',
                    compact('arr', 'userDetails', 'projectResources', 'metricValues', 'projectNames', 'fromdate', 'todate', 'users',
                        'roleId', 'designationName', 'practicesId', 'projectId', 'peopleId' ,'resourceRatings'));

            }
            return response()->json(['result' => false, 'msg' => 'Required Data is Not Available'], 422);
        }
        return response()->json(['result' => false, 'msg' => 'Oops, Select any one option...'], 422);
    }


    public function formPeopleDetails($detail)
    {
        $collection = collect($detail);

        $sorted = $collection->groupBy('peopleName');
        $i = 0;
        $result = [];
        foreach ($sorted as $key => $value) {
            $result[$i]['peopleName'] = $key;
            $result[$i]['managerName'] = $value->implode('managerName', ',');
            $result[$i]['projectName'] = $value->implode('projectName', ',');
            $result[$i]['roleName'] = $value->implode('roleName', ',');
            $result[$i]['peopleId'] = $value->implode('peopleId', ',');
            $result[$i]['projectId'] = $value->implode('projectId', ',');
            $result[$i]['managerId'] = $value->implode('managerId', ',');
            $i++;
        }
       
        return $result;
        
    }


    /**
     * It redirects to Project managers dashboard
     * @return response
     */

    public function getProjectManagerDashboard()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $userDetails = $this->userRepository->getRolesNameId();
        $projectData = $this->feedbackRepository->getdata();
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('feedback_form')
            ->with('projectData', $projectData)
            ->with('userDetails', $userDetails)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);


    }


    /**
     * @param Request $request
     * @return response
     * It redirects to Practice lead dashboard
     */
    public function getResourceRating($peopleId, $projectId, $managerId, $fromDate = null, $toDate = null, $role = null)
    {        
        $user = Auth::user();
        $roleId = $user->role_id;
        $getRoleName = $this->userRepository->getUserRoleName();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $peopleId = $this->encryptionService->decryptUrlParameter($peopleId);
        $projectId = $this->encryptionService->decryptUrlParameter($projectId);
        $managerId = $this->encryptionService->decryptUrlParameter($managerId);
        $userDetails = $this->userRepository->getRolesNameId();
        $userRatingDetails = $this->userRepository->getRatingDisplay($peopleId, $projectId, $managerId, $fromDate, $toDate, $role);
        $quarter_percent = $this->userRepository->getPercentageData($peopleId, $projectId, $managerId, $userRatingDetails);
        $userRatingComments = $this->userRepository->getMetricdata($peopleId, $projectId, $fromDate,
            $toDate, $role);
        foreach ($userRatingComments as $i => $value) {
            $userRatingComments[$i]['encPeopleId'] = $this->encryptionService->encryptUrlParameter($value['people_id']);
            $userRatingComments[$i]['encProjectId'] = $this->encryptionService->encryptUrlParameter($value['project_id']);
            $userRatingComments[$i]['encManagerId'] = $this->encryptionService->encryptUrlParameter($value['manager_id']);
        }
        $managerDetails = $this->userRepository->getManagerName($managerId);

        $managerRatingDetails = $this->formRatingDetails($managerDetails);

        if ($userRatingDetails[config('custom.allUserId')]['people_id']) {
            $user = User::withTrashed()->find($userRatingDetails[config('custom.allUserId')]['people_id'])->toArray();

        }
        return view('ratingSheet',
            compact('quarter_percent','userRatingDetails', 'userDetails', 'managerDetails', 'managerRatingDetails', 'user', 'roleId',
                'userRatingComments', 'peopleId', 'projectId', 'managerId', 'peopleIdd', 'projectIdd', 'managerIdd',
                'fromDate', 'toDate'))
            ->with('fromDate', $fromDate)
            ->with('toDate', $toDate)
            ->with('roleId', $roleId)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName)
            ->with('url', "/getResourceRating");
    }


    /**
     * Get Resources Rating By Role
     * @param $peopleId , $projectId, $managerId, $roleId
     * @return Response
     */
    public function getResourceRatingByRoleId($peopleId, $projectId, $managerId, $roleId)
    {
        return $this->getResourceRating($peopleId, $projectId, $managerId, $fromDate = null, $toDate = null, $roleId);
    }

    /**
     * Get Resources Rating By Quarter
     * @param $peopleId , $projectId, $managerId, $fromDate, $toDate, $roleId
     * @return Response
     */
    public function getResourceRatingByQuarter($peopleId, $projectId, $managerId, $fromDate, $toDate, $roleId = null)
    {
        return $this->getResourceRating($peopleId, $projectId, $managerId, $fromDate, $toDate, $roleId);
    }

    /**
     * Get Rating Details
     * @param $details
     * @return array
     */
    public function formRatingDetails($details)
    {
        $collection = collect($details);
        $sorted = $collection->groupBy('role_name');
        $i = 0;
        $result = [];
        foreach ($sorted as $key => $value) {
            $result[$i]['role_name'] = $key;
            $result[$i]['emp_id'] = $value->implode('emp_id', ',');
            $result[$i]['name'] = $value->implode('name', ',');
            $i++;
        }
        return $result;
    }


    /**
     * Log the user out of the application.
     * @return response
     */
    public function getLogout()
    {
        \Auth::logout();
        Session::forget('role');
        return redirect('/');
    }

    /**
     * It takes project manager to send mail to practice lead.
     * @return response
     */
    public function getMail()
    {
        return view('auth.mailmessage');
    }

    /**
     * Get complete resource details for practice lead.
     * @return response
     */
    public function getResourceData()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $userDetails = $this->userRepository->getRolesNameId();
        $getManagerId = $this->userRepository->getManagerId($userDetails);
        $getProjectLeadAndManagerId = $this->userRepository->getProjectLeadAndManagerId($userDetails, $getManagerId);
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($user->id);
        $getPeopleName = $this->userRepository->getPeopleId($getProjectLeadAndManagerId, $getHierarchicalIds);
        $projectDetails = $this->userRepository->getResourceDetails($userDetails);
        $practicesDetails = $this->userRepository->getPracticesDetails();
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('Plfeedback_form', compact('projectDetails', 'practicesDetails', 'getPeopleName', 'getRoleDetails', 'getRoleName'));
    }

    /**
     * Get Resource name based on projects.
     * @param integer
     * @return array $result
     */
    public function getResourceName($project_id)
    {
        $result = $this->feedbackRepository->getResourceByProjectId($project_id);
        return $result;
    }

    /**
     * Save the resource details by practice lead.
     * @param request
     * @return response
     */
    public function saveResourceData(StoreFeedbackRequest $request)
    {
        $userDetails = $this->userRepository->getRolesNameId();
        $this->feedbackRepository->getSave($userDetails);
        return redirect()->route('plfeedback.form');
    }

    /**
     * Sends mail
     * @param request
     * @return reponse
     */
    public function sendMailFunction(Request $request)
    {
        $emailId = $request->input('email');
        $resourceName = $request->input('resourceSelect');
        $data = array('email' => $emailId, 'name' => 'test', 'message' => 'test message');
        $message = "my message";

        \Mail::send('user', [
            'data' => $data
        ], function ($message) use ($data) {
            $message->from('amruthesh.r@compassitesinc.com', '');
            $message->to($data['email']);
            $message->subject('Performance Feedback Notification');
        });
        \Session::flash('success', 'Notification Sent Successfully');
        return \Redirect::to('mail');
    }

    /**
     * Getting ajax feedback form data
     * @param integer
     * @return reponse
     */
    public function feedback($dId)
    {
       if($dId > 0){
           $projectData = $this->feedbackRepository->getForm($dId);
           $user = USer::where('id',$dId)->first();
           if ($projectData) {
               return view('admin.feedback.feedback', compact('projectData', 'user'));
           }else{
               $message = "Metrics not found!";
               return view('admin.feedback.feedback', compact('message'));
           }
       }

    }

    /**
     * Getting ajax feedback form data
     * @param integer
     * @return reponse
     */
    public function editFeedback($dId, $feedbackId)
    {
        $feedbackTransactionData = $this->feedbackTransactionService->getFeedbackTransactionDataWithMetricsIdAsKey($feedbackId);
        $projectData = $this->feedbackRepository->getForm($dId);
        if ($projectData) {

            return view('admin.feedback.editFeedback', compact('projectData', 'feedbackTransactionData'));
        }
    }


    /**
     * Getting ajax feedback form data
     * @param integer
     * @return reponse
     */
    public function getResourceDetailsOnPracticeLead($people_id, $startdate, $enddate)
    {
        $user = Auth::user();
        $projectData = $this->feedbackRepository->getForm($people_id);
        $resourceRatingDetails = $this->userRepository->getResourceRatingDetails($people_id, $startdate, $enddate, $user);
        $feedbackTransactionData = [];
        foreach ($resourceRatingDetails as $feedbackTransaction) {

            $feedbackTransactionData[$feedbackTransaction->feedback_metrics_id] = $feedbackTransaction->toArray();
        }
        if ($feedbackTransactionData) {
            return view('admin.feedback.plFeedbackForm', compact('projectData', 'feedbackTransactionData'));
        }
        return view('admin.feedback.feedback', compact('projectData', 'feedbackTransactionData'));


    }

    public function getNotification()
    {
        if (Session::get('role') == config('custom.projectManagerId')) {
            // $currentDate= date('Y-m-d');echo $currentDate;
            //dd($notification);
            $user = Auth::user();
            $navigatorForManager = $this->userRepository->getNavigator($user);
            $result = [];
            foreach ($navigatorForManager as $nfm) {
                $notification = PeopleFeedback::where('people_id', '=', $nfm['people_id'])->where('start_date', '<', '2016-03-01')->where('end_date', '>', '2016-03-31')->first();
                if ($notification) {
                    print_r("Test");
                } else {
                    $result[] = $nfm['people_id'] . 'FB not available';
                }
            }
            return response()->json($result);
        } else {
            return "You are not ProjectManager";
        }
    }

    public function getOrganisationChart()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        return view('org_tree')
            ->with('getRoleDetails',$getRoleDetails);
    }


    if ($request->hasFile('excel_file')) {
 $res = Excel::load($request->file('excel_file'), function($reader) {
 return $reader;
 })->get();
//      return $res;
        foreach($res[0] as $r){
                $input['name'] = $r['name'];
                $input['mobile'] = $r['mobile'];
                $input['country_id'] = $request->country_id;
                $input['city_id'] = $request->city_id;
 Customer::create($input);
 }
}

}
