<?php
//Admincontroller
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNavigatorRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UtilityRepository;
use Illuminate\Http\Request;
use App\Services\FeedbackTransactionService;
use App\Services\EncryptionService;
use Redirect;
use Session;
use Auth;

class AdminController extends Controller
{
    /**
     * Instances of User Repository
     */
    protected $userRepository;
    /**
     * Instances of Admin Repository
     */
    protected $adminRepo;
    protected $reportRepo;
    /**
     * Access all methods and objects in Repository
     */
    /**
     * @var FeedbackTransactionService
     */
    private $feedbackTransactionService;
    private $encryptionService;

    public function __construct(
        ReportRepository $reportRepo,
        AdminRepository $adminRepo,
        UserRepository $userRepository,
        UtilityRepository $utilityRepository,
        EncryptionService $encryptionService,
        FeedbackTransactionService $feedbackTransactionService
    )
    {
        parent::__construct();
        $this->reportRepo = $reportRepo;
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->utilityRepository = $utilityRepository;
        $this->encryptionService = $encryptionService;
        $this->feedbackTransactionService = $feedbackTransactionService;
    }

    /**
     * Show the form for creating a new people.
     * @return response
     */
    public function create()
    {
        $userDetails = $this->userRepository->getRolesNameId();
        $formData = $this->adminRepo->getFormDetails();
        $navigatorsData = $this->adminRepo->showNavigatorsDetails();
        $delete = false;
        return view('admin.navigators.index', compact('formData', 'navigatorsData', 'userDetails', 'delete'));
    }

    /**
     * Show the form for creating a new people.
     * @return response
     */

    /**
     * Store a newly created people in storage.
     * @param Request $request
     * @return response
     *
     */
    public function storeNavigator(StoreNavigatorRequest $request)
    {
        $validator = \Validator::make($request->except('_token'), $this->adminRepo->validationRules);
        if ($validator->fails()) {
            return redirect()->route('admin.dasboard')->withErrors($validator);
        }
        $data = $this->adminRepo->insertData($request);
        if ($data == true) {
            Session::flash('message', "Navigator Assigned Successfully");
            return Redirect::back();
        }
        Session::flash('message', "Failed to Update");
        return Redirect::back();
    }

    /**
     * To show the existing project details
     * @return response
     */
    public function showProject()
    {
        $userDetails = $this->userRepository->getRolesNameId();
        $getProjectDetails = $this->adminRepo->getProjectData();
        return view('admin.admin_creates_project')
            ->with(['getProjectDetails' => $getProjectDetails, 'userDetails' => $userDetails]);
    }

    /**
     * To Save project created to db
     * @param Request $request
     * @return response
     *
     */
    public function storeProject(StoreProjectRequest $request)
    {
        $validator = \Validator::make($request->except('_token'), $this->adminRepo->validationRulesProject);
        if ($validator->fails()) {
            return redirect()->route('admin.navigators.roles')->withErrors($validator);
        }
        $getData = $this->adminRepo->insertProjectData($request);
        if ($getData == true) {
            Session::flash('message', "Project Created Successfully");
            return Redirect::back();
        }
        Session::flash('message', "Failed to Create Project");
        return Redirect::back();

    }

    /**
     * Assign Metrics to User
     * @return response
     */
    public function assignMetrics()
    {
        $userDetails = $this->userRepository->getRolesNameId();
        $userMetrics = $this->adminRepo->getuserMetrics();
        $userDesignation = $this->adminRepo->getuserDesignation();
        return view('admin.metrics.assigns_metrics', compact('userDetails', 'userMetrics', 'userDesignation'));
    }

    /**
     * Storing Metrics to the database
     * @return response
     */
    public function storeMetrics(Request $request)
    {
        $validator = \Validator::make($request->except('_token'), $this->adminRepo->validationRulesMetric);
        if ($validator->fails()) {
            return redirect()->route('admin.navigators.metrics')->withErrors($validator);
        }
        $metricData = $this->adminRepo->insertMetrics($request);

        if ($metricData->exists()) {
            Session::flash('message', "Metric Assigned Successfully");
            return Redirect::back();
        }
        Session::flash('message', "Failed to Assigned");
        return Redirect::back();
    }

    /**
     * Getting Reports view to admin
     * @param request
     * @return response
     *  It returns to admin report view
     */
    public function getadminDashboard(Request $request)
    {
        $roleId = Auth::user()->role_id;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        $userDetails = $this->userRepository->getRolesNameId();
        $getManagerId = $this->userRepository->getManagerId($userDetails);
        $getProjectLeadAndManagerId = $this->userRepository->getProjectLeadAndManagerId($userDetails, $getManagerId);
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($user->id);
        $projectNames = $this->adminRepo->getProjectData($getProjectLeadAndManagerId,$getHierarchicalIds);
        $practiceNames = $this->adminRepo->getPracticeData();
        $getPeopleName = $this->userRepository->getPeopleId($getProjectLeadAndManagerId, $getHierarchicalIds);
        $metricValues = $this->userRepository->getMetrics();
        $practicesDetails = $this->userRepository->getPracticesDetails();
        $projectResources = $this->userRepository->getProjectResources();
        $users = $this->reportRepo->getPeople();
        $arr = $this->userRepository->formPeopleDetails($projectResources);
        foreach ($arr as $i => $value) {
            $arr[$i]['encPeopleId'] = $this->encryptionService->encryptUrlParameter($value['peopleId']);
            $arr[$i]['encProjectId'] = $this->encryptionService->encryptUrlParameter($value['projectId']);
            $arr[$i]['encManagerId'] = $this->encryptionService->encryptUrlParameter($value['managerId']);
        }
        return view('practiceLead',
            compact('arr', 'projectResources', 'userDetails', 'metricValues', 'projectNames', 'getPeopleName', 'practicesDetails', 'users',
                'roleId', 'getRoleDetails', 'getRoleName', 'practiceNames'))->withInput($request->except('password'));
    }


}
