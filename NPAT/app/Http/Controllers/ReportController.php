<?php

namespace App\Http\Controllers;

use App\Models\FeedbackMetrics;
use App\Models\PeopleFeedback;
use App\Models\Practices;
use App\Models\Project;
use App\Models\User;
use App\Repositories\AdminRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;
use App\Repositories\UtilityRepository;
use App\Services\EncryptionService;
use App\Services\FeedbackTransactionService;
use Illuminate\Http\Request;
use Input;
use Session;
use Auth;
use Redirect;

/**
 * Description of ReportController
 *
 * @author rohini
 */
class ReportController extends Controller
{
    protected $reportRepo;
    protected $feedbackRepository;
    private $encryptionService;
    private $feedbackTransactionService;
    private $adminRepo;
    private $utilityRepository;

    public function __construct(
        ReportRepository $reportRepo,
        FeedbackRepository $feedbackRepository,
        UserRepository $userRepository,
        EncryptionService $encryptionService,
        FeedbackTransactionService $feedbackTransactionService,
        AdminRepository $adminRepo,
        UtilityRepository $utilityRepository
    )
    {
        $this->reportRepo = $reportRepo;
        $this->feedbackRepository = $feedbackRepository;
        $this->encryptionService = $encryptionService;
        $this->userRepository = $userRepository;
        $this->feedbackTransactionService = $feedbackTransactionService;
        $this->adminRepo = $adminRepo;
        $this->utilityRepository = $utilityRepository;
    }

    public function getProjectManagerDashboardView()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $users = $this->reportRepo->getPeople();
        $userDetails = $this->userRepository->getRolesNameId();
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('projectManagerReport')
            ->with('users', $users)
            ->with('userDetails', $userDetails)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);
    }

    /**
     * Get the people name and project name
     *
     * @param  array $request
     * @return User
     */
    public function getResourceBetweenDates()

    {
        $resource_id = Input::get('resource');
        $start_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('start_date'));
        $end_date = $this->utilityRepository->getDateFormat("Y-m-t", Input::get('end_date'));
        $projectUsers = $this->reportRepo->getProjectDetails($start_date, $end_date, $resource_id);
        if ($projectUsers) {
            return view('projectManagerReportSheet')
                ->with('projectUsers', $projectUsers);
        }
        return response()->json(['result' => false, 'msg' => 'Required Data is Not Available'], 422);
    }

    /**
     * Getting the rating details.
     *
     * @param  array $peopleId $projectId
     * @return $ratingValue
     */
    public function ratingDetailsForPeople($peopleid, $projectid, $fromDate, $toDate)
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $userDetails = $this->userRepository->getRolesNameId();
        $ratingValue = $this->reportRepo->ratingDetailsForPeople($peopleid, $projectid, $fromDate, $toDate);
        $peopleName = $this->userRepository->getProjectResources();
        if ($ratingValue['values'][0]->people_id) {
            $user = User::withTrashed()->find($ratingValue['values'][0]->people_id)->toArray();
        }
        return view('resourceRatingSheet', compact('userDetails', 'ratingValue','getRoleDetails'))
            ->with('peopleName', $peopleName)->with('fromDate', $fromDate)
            ->with('toDate', $toDate)->with('user', $user);
    }

    /**
     * Edit the rating details.
     *
     * @param Integer
     * @return Response
     */
    public function editFeedbackForm($recordId, $start_date, $end_date)
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $projectData = $this->feedbackRepository->getdata();
        $feedbackData = PeopleFeedback::find($recordId)->toArray();
        $userProjectData = Project::where('id', $feedbackData['project_id'])->first();
        $practiceData = Practices::where('id', $feedbackData['project_id'])->first();
        $peopleData = User::where('id', $feedbackData['people_id'])->first();
        $quarterYear = $this->utilityRepository->getYearFromDate($start_date,$end_date);
        return view(
            'feedbackFormEdit',
            [   
                'project' => $projectData['project'],
                'expectation' => $projectData['expectation'],
                'resource' => $projectData['resource'],
                'metrics' => $projectData['metrics'],
                'categorydetails' => $projectData['categorydetails'],
                'projectData' => $projectData,
            ]
        )->with('feedbackData', $feedbackData)
            ->with('peopleData', $peopleData)
            ->with('practiceData', $practiceData)
            ->with('userProjectData', $userProjectData)
            ->with('recordId', $recordId)
            ->with('startdate', $start_date)
            ->with('enddate', $end_date)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('quarterYear', $quarterYear);
          }

    /**
     * Get Resource Rating Details for Resource
     * @return mixed
     */
    public function resourceFeedbackDetails()
    {
        $projectId = Input::get('resource-project');
        $start_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('start_date'));
        $end_date = $this->utilityRepository->getDateFormat("Y-m-t", Input::get('end_date'));
        $projectDetails = $this->reportRepo->getProjectDetailsBasedOnResource();
        $projectUsers = $this->reportRepo->getResourceRatingDetails($startdate, $enddate, $projectId);
        $arraydata = $this->userRepository->formPeopleDetails($projectUsers);
        foreach ($arraydata as $i => $value) {
            $arraydata[$i]['encPeopleId'] = $this->encryptionService->encryptUrlParameter($value['peopleId']);
            $arraydata[$i]['encProjectId'] = $this->encryptionService->encryptUrlParameter($value['projectId']);
            $arraydata[$i]['encManagerId'] = $this->encryptionService->encryptUrlParameter($value['managerId']);
        }
        if ($arraydata) {
            return view('resourceRating')
                ->with('projectDetails', $projectDetails)
                ->with('projectUsers', $projectUsers)
                ->with('arraydata', $arraydata)
                ->with('startdate', $startdate)
                ->with('enddate', $enddate);
        }
        return response()->json(['result' => false, 'msg' => 'Required Data is Not Available'], 422);


    }

    /**
     * Get People Dashboard
     * @return mixed
     */
    public function getPeopleDashboard()
    {
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $userDetails = $this->userRepository->getRolesNameId();
        $projectDetails = $this->adminRepo->getProjectData();
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('resource')
            ->with('userDetails', $userDetails)
            ->with('projectDetails', $projectDetails)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);
    }

    /**
     * Get Report Summary Dashboard
     * @return $this
     */
    public function getReportDashboard()
    {
        $roleId = Auth::user()->role_id;
        $userId = Auth::user()->id;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        if (Session::get('role') == config('custom.practiceLeadId')) {
            $userDetails = $this->userRepository->getRolesNameId();
            $getManagerId = $this->userRepository->getManagerId($userDetails);
            $getProjectLeadAndManagerId = $this->userRepository->getProjectLeadAndManagerId($userDetails,
                $getManagerId);
            $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId);
            $users = $this->userRepository->getPeopleId($getProjectLeadAndManagerId, $getHierarchicalIds);
            return view('report_summary')
                ->with('roleId', $roleId)
                ->with('users', $users)
                ->with('getRoleDetails', $getRoleDetails)
                ->with('getRoleName', $getRoleName);
        }

        $users = $this->reportRepo->getPeople();
        return view('report_summary')
            ->with('roleId', $roleId)
            ->with('users', $users)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);
    }

    /**
     * Redirect to Report Summary Json Data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportSummary()
    {
        $roleId = Auth::user()->role_id;
        $userId = Auth::user()->id;
        $fromyear = Input::get('fromyear');
        $toyear = Input::get('toyear');
        $peopleId = Input::get('resource');
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId);
        if (Session::get('role') != 2) {
            $getCountOfRatings = $this->reportRepo->getCountOfRatings($fromyear, $toyear, $peopleId, $getHierarchicalIds);
            if ($getCountOfRatings) {
                return view('report_summary_sheet')
                    ->with('fromyear', $fromyear)
                    ->with('toyear', $toyear)
                    ->with('peopleId', $peopleId)
                    ->with('getCountOfRatings', $getCountOfRatings)
                    ->with('roleId', $roleId);
            }
        }
        $getCountOfRatingsForPracticeLead = $this->reportRepo->getCountOfRatingsForPracticeLead($fromyear, $toyear, $peopleId, $getHierarchicalIds);
        if ($getCountOfRatingsForPracticeLead) {
            return view('report_summary_sheet_practiceLead')
                ->with('fromyear', $fromyear)
                ->with('toyear', $toyear)
                ->with('peopleId', $peopleId)
                ->with('getCountOfRatingsForPracticeLead', $getCountOfRatingsForPracticeLead)
                ->with('roleId', $roleId);
        }

        return response()->json(['result' => false, 'msg' => 'Required Data is Not Available'], 422);
    }


    /**
     * Get Reporting Manager Name Based on Designation
     * @return $this|\Illuminate\Database\Query\Builder|static
     */
    public function getReportManagerListForUserRegisterPractices()
    {
        $resourcePractices = Input::get('practices');
        $getReportManagerByPractice = $this->userRepository->getReportingManagerIdByPractice($resourcePractices);
        return $getReportManagerByPractice;
    }

    public function getResourcesListForPractices(){

        $userId = Auth::user()->id;
        $practicesId = Input::get('practicesId');
        $getHierarchicalIds = $this->userRepository->getReportersInHeirarchy($userId,2);
        return $this->userRepository->getResourcesListForPractices($getHierarchicalIds, $practicesId);

    }

    /**
     * Get Resources Based on Project
     * @return mixed
     */
    public function getResourcesOnProject(){
        
        $projectId = Input::get('projectId');
        return $this->userRepository->getResourcesOnProject($projectId);
    }

    public function getDeliveryListForUserRegisterPractices()
    {
        return $this->userRepository->getDeliveryIdByPractice();
    }

    /**
     * Get sort value for category
     * @return mixed
     */
    public function getCategorySortValue(){

        $categoryId = Input::get('categoryId');
        return $this->reportRepo->getSortValueFromCategoryValue($categoryId);

    }

    public function updateResourceStatus()
    {
        $getRowID = Input::get('rowId');
        $getRowStatus = Input::get('action');
        $returnUserStatus = $this->reportRepo->resourceStatusUpdate($getRowID, $getRowStatus);
        return response()->json(['result' => true,
                                  'msg' => ($returnUserStatus['status'] == 0) ? 'User Deactivated Successfully' : 'User Activated Successfully']);
    }
}


