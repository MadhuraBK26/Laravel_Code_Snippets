<?php

namespace App\Repositories;

use App\Models\DesignationFeedbackMetric;
use App\Models\Expectation;
use App\Models\FeedbackMetrics;
use App\Models\FeedbackTransaction;
use App\Models\KraCategory;
use App\Models\NavigatorDesignation;
use App\Models\ProjectManager;
use App\Models\PeopleFeedback;
use App\Models\Practices;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Auth;
use DB;
use Config;
use Session;


class UserRepository
{
    /**
     * Instance for repository
     */
    protected $feedbackRepository;
    protected $adminRepo;
    protected $reportRepo;
    protected $user;
    protected $feedbackTransactionRepository;

    /**
     * Constructor for repository to access all the methods in repository
     */

    public function __construct(
        FeedbackRepository $feedbackRepository,
        AdminRepository $adminRepo,
        ReportRepository $reportRepo,
        FeedbackTransactionRepository $feedbackTransactionRepository,
        User $user
    )
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->adminRepo = $adminRepo;
        $this->reportRepo = $reportRepo;
        $this->feedbackTransactionRepository = $feedbackTransactionRepository;
        $this->user = $user;
    }

    /**
     * Validation rules for register form
     */
    public $validationRules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|exists:roles,id',
        'password' => 'required|confirmed',
        'password_confirmation' => 'required',
    ];

    /**
     * Registering new users
     * @return roles of user
     */

    public function getAllRoles()
    {
        $roles = Role::all()->toArray();

        return $roles;
    }

    /**
     * Designation for dropdown to register users
     * @return designation
     */
    public function getAllDesignation()
    {
        return NavigatorDesignation::all()->toArray();
    }

    /**
     * Getting Reporting Manager Based on Designation
     * @param $resourcePractices
     * @return $this|\Illuminate\Database\Query\Builder|static
     */

    public function getReportingManagerIdByPractice($resourcePractices)
    {
        return DB::table('users')
            ->select('users.id', 'users.name')
            ->leftjoin('practices_user', 'practices_user.user_id', '=', 'users.id')
            ->leftjoin('practices', 'practices.id', '=', 'practices_user.practices_id')
            ->where('practices_user.practices_id', '=', $resourcePractices)->where('users.navigator_designation_id', '=', 20)->distinct()->get();
    }

    /**
     * Getting Delivery Head Based on Practice
     * @return mixed
     */
    public function getDeliveryIdByPractice()
    {
        return DB::table('users')
            ->select('users.id', 'users.name')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.role_id', config('custom.DeliveryHead'))->get();
    }

    /**
     * Get List of Resources
     * @param $practicesId
     * @return mixed
     */
    public function getResourcesListForPractices($getHierarchicalIds, $practicesId)
    {
        if (Auth::user()->role_id == config('custom.DeliveryHead')) {
            $deliveryHeadData =  DB::table('users')
                ->leftjoin('practices_user', 'practices_user.user_id', '=', 'users.id')
                ->leftjoin('practices', 'practices.id', '=', 'practices_user.practices_id')
                ->where('practices.id', $practicesId);
                if(Auth::user()->navigator_designation_id == config('custom.DirectorofDelivery')){
                    $deliveryHeadData = $deliveryHeadData->where('users.reporting_manager_id', Auth::user()->id);
                }else{
                    $deliveryHeadData = $deliveryHeadData->whereIn('users.id', $getHierarchicalIds);
                }

            $deliveryHeadData =$deliveryHeadData->whereNotIn('users.id', [Auth::user()->id])->get();
            return $deliveryHeadData;
        }
        return DB::table('users')
            ->leftjoin('practices_user', 'practices_user.user_id', '=', 'users.id')
            ->leftjoin('practices', 'practices.id', '=', 'practices_user.practices_id')
            ->where('practices.id', $practicesId)
            ->whereNotIn('users.id', [Auth::user()->id])
            ->groupBy('users.name')->get();
    }

    /**
     * Get Resources On Project Id
     * @param $projectId
     * @return mixed
     */
    public function getResourcesOnProject($projectId){
        $userId = Auth::user()->id;
        $getHierarchicalId = $this->getReportersInHeirarchy($userId);
        $projectNavigatorData =  DB::table('project_manager')
            ->join('users as manager', 'project_manager.manager_id', '=', 'manager.id')
            ->join('users as user', 'project_manager.people_id', '=', 'user.id')
            ->join('project','project.id','=','project_manager.project_id')
            ->where('project_manager.project_id',$projectId);
        if(Auth::user()->emp_id == config('custom.PMOId')){
            $projectNavigatorData = $projectNavigatorData
                ->whereIn('project_manager.manager_id', $getHierarchicalId)
                ->whereIn('project_manager.people_id', $getHierarchicalId)
                ->select('manager.id','manager.name','user.id', 'user.name')
                ->groupBy('manager.name')
                ->groupBy('user.name');
        }else{
            $projectNavigatorData = $projectNavigatorData->whereIn('project_manager.people_id', $getHierarchicalId)
                ->select('user.id', 'user.name')
                ->groupBy('user.name');
        }
        $projectNavigatorData = $projectNavigatorData->get();
        return $projectNavigatorData;
    }

    /**
     * Get User Role Id
     * @return roles id
     */
    public function getRolesId()
    {
        $roleId = 0;
        if (Auth::check()) {
            $roles = Auth::user()->roles;
            if (count($roles) > 0) {
                $roleId = Auth::user()->roles[0]->id;
            }
        }

        return $roleId;
    }

    /**
     * Get Role Names and Ids
     * @return array user details
     */
    public function getRolesNameId()
    {
        if (Auth::user()) {
            $userRoles = Auth::user();
            $userData = array('id' => $userRoles['id'], 'name' => $userRoles['name'], 'email' => $userRoles['email']);
            $userDataDetails = User::select('users.id', 'users.name', 'users.email', 'roles.id as roleId', 'roles.name as roleName')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('users.id', $userData['id'])->get()->toArray();
            $userDetails = array('id' => $userDataDetails[0]['id'], 'name' => $userDataDetails[0]['name'], 'roleId' => $userDataDetails[0]['roleId'], 'roleName' => $userDataDetails[0]['roleName']);
              /*  dd($userDetails);
                exit;*/

            return $userDetails;
        }
    }

    /**
     * Get Metrics
     * @return array metric values
     */
    public function getMetrics()
    {
        return FeedbackMetrics::get(['metrics'])->toArray();
    }

    /**
     * Get PeopleName , ManagerName and ProjectName
     * @return array project users
     */

    public function getProjectResources($getHierarchicalId = null)
    {
        $projectUsers = User::join('people_feedback', 'people_feedback.people_id', '=', 'users.id')
            ->leftjoin('users as manager', 'people_feedback.manager_id', '=', 'manager.id')
            ->leftjoin('project', 'people_feedback.project_id', '=', 'project.id')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftjoin('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id');
        if ($getHierarchicalId) {

            $projectUsers = $projectUsers->whereIn('users.id', $getHierarchicalId);

        }
        $projectUsers = $projectUsers->select('project.name as projectName', 'people_feedback.project_id as projectId',
            'users.name as peopleName', 'manager.name as managerName', 'people_feedback.people_id as peopleId',
            'people_feedback.manager_id as managerId',
            'people_feedback.start_date', 'people_feedback.end_date', 'roles.name as roleName', 'users.navigator_designation_id as designationId', 'navigator_designations.name as designationName' ,

            'people_feedback.id', 'users.reporting_manager_id as reportingManagerId')
            ->groupBy('projectName')
            ->groupBy('peopleName')
            ->get()
            ->toArray();
        foreach ($projectUsers as $user) {
            $user['ratings'] = $this->feedbackTransactionRepository->getMetricExpectationId($user);
        }

        return $projectUsers;
    }

    /**
     * Redirecting routes based on roles
     * @return view based on roles
     */
    public function getViewOrRedirect()
    {
        $redirect['redirect'] = false;
        $roleId = $this->getRolesId();
        $userDetails = $this->getRolesNameId();
        switch ($roleId) {
            case Config::get('roles.delivery-head'):
                $redirect['view'] = 'welcome';
                break;
            case
            Config::get('roles.people'):
                $redirect['view'] = 'resource';
                $projectDetails = $this->reportRepo->getProjectDetailsBasedOnResource();
                $redirect['data'] = [
                    'userDetails' => $userDetails,
                    'projectDetails' => $projectDetails,
                ];

                break;
            case Config::get('roles.practice-lead'):
                $redirect = config('custom.projectLeadId');
                break;
            case Config::get('roles.project-manager'):
                $projectData = $this->feedbackRepository->getdata();
                $redirect['view'] = 'feedback_form';

                $redirect['data'] = [
                    'userDetails' => $userDetails,
                    'projectData' => $projectData
                ];

                break;
            case Config::get('roles.admin'):
                $userDetails = $this->getRolesNameId();
                $formData = $this->adminRepo->getFormDetails();
                $navigatorsData = $this->adminRepo->showNavigatorsDetails();
                $redirect['view'] = 'admin.navigators.index';
                $redirect['data'] = [
                    'userDetails' => $userDetails,
                    'formData' => $formData,
                    'navigatorsData' => $navigatorsData,
                ];
                break;
        }

        return $redirect;
    }

    /**
     * Returning rating data based on peopleId and projectId
     * @param $peopleId int , $projectId int, $startdate timestamp, $enddate timestamp
     * @return array rating values
     */

    public function getRatingDisplay($peopleId, $projectId, $managerId, $fromDate, $toDate, $role = null)
    {
        $peopleId = explode(',', $peopleId);

        $metrics = FeedbackMetrics::join('feedback_transaction', 'feedback_metrics.id', '=',
            'feedback_transaction.feedback_metrics_id')
            ->join('people_feedback', 'people_feedback.id', '=', 'feedback_transaction.people_feedback_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->join('kra_category', 'kra_category.id', '=', 'feedback_metrics.category_id')
            ->where('people_feedback.type', '!=', '')
            ->whereIn('people_feedback.people_id', $peopleId)
            ->select('people_feedback.people_id', 'people_feedback.project_id', 'feedback_metrics.id',
                'metrics','feedback_metrics.category_id','kra_category.name','people_feedback.start_date','people_feedback.end_date','users.navigator_designation_id as designation_id')
            ->groupBy('metrics')
            ->orderBy('category_id','ASC')
            ->orderBy('feedback_metrics.id')
            ->get()->toArray();

        $quarter = $this->feedbackTransactionRepository->getQuarterCount($peopleId, $projectId, $managerId, $fromDate, $toDate);

        $getMetricData = $this->feedbackTransactionRepository->getMetricsWithQuarter($quarter, $metrics, $peopleId, $projectId, $managerId, $fromDate, $toDate);

        return $getMetricData;
    }

    /**
    * To get the percentage respect to the metrics attained by the resource
    * percentage will differe depends upon the designation
    * @return array overall rating for different quaters
    **/

    public function getPercentageData($peopleId, $projectId, $managerId, $getMetricData)
    {
        $designation_id = User::where('id',$peopleId)->pluck('navigator_designation_id');
        $categories = KraCategory::where('status',1)->get();
        $peopleId = explode(',', $peopleId);
        $projectId = explode(',', $projectId);
        $managerId = explode(',', $managerId);
        $ratingTotalCount = count($getMetricData[0]['values']);
        $percentage = [];
        $quarter_percentage = [];

        $category_percentage = $this->getCategoryPercentageValues($peopleId);

        for($i=0;$i<$ratingTotalCount;$i++) {
            if( !empty($getMetricData[0]['values'][$i]) ){
                $start_date = $getMetricData[0]['values'][$i]['start_date'];
                $end_date = $getMetricData[0]['values'][$i]['end_date'];

                if(isset($category_percentage[1]) && $category_percentage[1] == 0) {
                    $percentage[$i][] = 0;

                }elseif (isset($category_percentage[0]) && $category_percentage[0] == 100) {
                    $percentage[$i][] = $this->feedbackMetric_perPeople($start_date, $end_date, $i, $designation_id, 0, $peopleId, $projectId, $managerId, $getMetricData, $category_percentage);

                }else{
                    foreach ($categories as $category) {
                        $percentage[$i][$category->id] = $this->feedbackMetric_perPeople($start_date, $end_date, $i, $designation_id, $category->id, $peopleId, $projectId, $managerId, $getMetricData, $category_percentage);
                    }
                }
            }
        }

        foreach($percentage as $value){
            $quarter_percentage[] = round(array_sum($value), 2);
        }

        return $quarter_percentage;
    }

    function feedbackMetric_perPeople($start_date,$end_date,$i,$designation_id,$cat_id,$peopleId,$projectId,$managerId,$getMetricData,$category_percentage){

        $metricsrowdata = FeedbackMetrics::join('feedback_transaction', 'feedback_metrics.id', '=',
            'feedback_transaction.feedback_metrics_id')
            ->join('designation_feedback_metric','designation_feedback_metric.metrics_id','=','feedback_metrics.id')
            ->join('people_feedback', 'people_feedback.id', '=', 'feedback_transaction.people_feedback_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->join('kra_category', 'kra_category.id', '=', 'feedback_metrics.category_id')
            ->where('designation_feedback_metric.is_mandatory', 1)
            ->where('designation_feedback_metric.navigator_designation_id', $designation_id)
            ->where('people_feedback.type', '!=', '');

            if($cat_id > 0){
                $metricsrowdata = $metricsrowdata->where('feedback_metrics.category_id','=',$cat_id);
            }

            $metricsrowdata = $metricsrowdata->whereIn('people_feedback.people_id', $peopleId)
            ->whereIn('people_feedback.project_id', $projectId)
            ->whereIn('people_feedback.manager_id', $managerId)
            ->where('people_feedback.start_date', '>=', $start_date)
            ->where('people_feedback.end_date', '<=', $end_date)
            ->select(DB::Raw('count(*) as metrics_count, sum(feedback_transaction.expectation_id) as metrics_sum'),'people_feedback.type')
            ->orderBy('category_id','ASC')
            ->get()->toArray();

        if($metricsrowdata && ($metricsrowdata[0]['metrics_count'] > 0 || $metricsrowdata[0]['metrics_sum'] > 0) ){
            $category_average = $metricsrowdata[0]['metrics_sum'] / $metricsrowdata[0]['metrics_count'];
            return $percentage = $category_average * ($category_percentage[$cat_id]/100);
        }else{
            return 0;
        }
    }

    /**
     * Get PeopleName , ManagerName and ProjectName based on date
     * @param $startdate timestamp, $enddate timestamp
     * @return array project users
     */

    public function getUserDetailsBasedOnDate($projectId, $startDate, $endDate,
                $peopleId=null, $practicesId, $getHierarchicalIds=null)
    {
        $projectUsers = User::join('people_feedback', 'people_feedback.people_id', '=', 'users.id')
            ->leftjoin('users as manager', 'people_feedback.manager_id', '=', 'manager.id')
            ->leftjoin('project', 'people_feedback.project_id', '=', 'project.id')
            ->leftjoin('practices', 'people_feedback.project_id', '=', 'practices.id')
            ->leftjoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftjoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftjoin('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id');
        if (Session::get('role') != config('custom.projectManagerLead')) {
            if ($getHierarchicalIds) {
                $projectUsers = $projectUsers->whereIn('users.id', $getHierarchicalIds);
            }
        }
        if ($projectId) {
            $projectUsers = $projectUsers->where('people_feedback.project_id', '=', $projectId)
                ->where('people_feedback.type','=',1);
        }
        if ($practicesId) {
            $projectUsers = $projectUsers->where('people_feedback.project_id', '=', $practicesId)
                ->where('people_feedback.type','=',2);;
        }
        if ($peopleId) {
            $projectUsers = $projectUsers->where('people_feedback.people_id', '=', $peopleId);
        }
        if ($startDate && $endDate) {

            $projectUsers = $projectUsers->where('people_feedback.start_date', '>=', $startDate)
                ->where('people_feedback.end_date', '<=', $endDate);
        }

        return $projectUsers->select(DB::raw('(CASE when people_feedback.type=1 then project.name else practices.practices end) as projectName'), 'people_feedback.type' ,'people_feedback.project_id as projectId',
            'users.name as peopleName', 'manager.name as managerName', 'people_feedback.people_id as peopleId',
            'people_feedback.manager_id as managerId', 'roles.name as roleName', 'people_feedback.id as recordId',
            'users.reporting_manager_id as reportingManagerId',
            'people_feedback.project_id as practicesId',
            'people_feedback.start_date', 'people_feedback.end_date', 'role_user.role_id as roleId', 'users.navigator_designation_id as designationId', 'navigator_designations.name as designationName' ,
            'people_feedback.id')
            ->withTrashed()
            ->groupBy('projectName')
            ->groupBy('peopleName')
            ->groupBy('roleName')
            ->orderBy('people_feedback.people_id')
            ->get()->toArray();
    }

    public function getResourceRatings($projectUsers){
        $i=0;
        $userRating = [];
        foreach ($projectUsers as $user) {

            $percentage = [];
            $categories = KraCategory::where('status',1)->get();
            $category_percentage = $this->getCategoryPercentageValues($user['peopleId']);

            // if the 1st percent is 0 then it is 0 for other categories too
            if(isset($category_percentage[1]) && $category_percentage[1] == 0){
                $percentage[] = 0;

            }elseif(isset($category_percentage[0]) &&  $category_percentage[0] == 100){
                $percentage[] = $this->getCategoryAverage($user, 0, $category_percentage);

            }else{
                foreach($categories as $category){

                    $percentage[$category->id] = $this->getCategoryAverage($user, $category->id, $category_percentage);
                }
            }

            // rounding the overall rating to floating point of 2 digits
            $userRating[$user['peopleId']][$user['type']] = round(array_sum($percentage), 2);
            $i++;
        }

        return $userRating;
    }

    // getting category percentage for the user
    function getCategoryAverage($user, $cat_id, $category_percentage){

        $user['ratings'] = $this->feedbackTransactionRepository->getfeedbackMetrics($user, $cat_id);

        if($user['ratings'] && $user['ratings'][0]['sum'] > 0 && $user['ratings'][0]['count'] > 0){
            $average = $user['ratings'][0]['sum'] / $user['ratings'][0]['count'];
            $cat_percentage = $average * ($category_percentage[$cat_id] / 100);

        }else{
            // if count of metrics or sum is 0 then average is also 0
            $cat_percentage = 0;
        }
        return $cat_percentage;

    }

    // picking the metric category percentage corresponding to the designation
    function getCategoryPercentageValues($peopleId){

        if(is_array($peopleId) == true){
            $peopleId = array_unique($peopleId);
            $personId = $peopleId[0];
        }else{
            $personId = $peopleId;
        }

        if($personId){
            $designation_id = User::where('id',$personId)->pluck('navigator_designation_id');
            switch ($designation_id) {
                case (in_array($designation_id, config('custom.designation_group1'))):
                    return config('custom.metric_percentage1');
                    break;

                case (in_array($designation_id, config('custom.designation_group2'))):
                    return config('custom.metric_percentage2');
                    break;

                case (in_array($designation_id, config('custom.designation_group3'))):
                    return config('custom.metric_percentage3');
                    break;

                case (in_array($designation_id, config('custom.designation_group4'))):
                    return config('custom.metric_percentage4');
                    break;

                default:
                    return config('custom.metric_percentage5');
                    break;
            }
        }else{
            return config('custom.metric_percentage5');
        }
    }

    /**
     * Get project data
     */
    public function getFeedback()
    {
        $projectData = $this->feedbackRepository->getdata();
    }

    /**
     * Retrieve the resource details in feedback form.
     * @return array $final data
     */
    public function getResourceDetails($userDetails)
    {
        $userRoles = Auth::user();
        $project = Project::join('project_manager', 'project_manager.project_id', '=', 'project.id');
        if ($userDetails['roleId'] == config('roles.project-manager')) {
            $project = $project->where('project_manager.manager_id', '=', $userDetails['id']);
        }
        $project = $project->groupBy('project.name')
            ->get(['project.id', 'project.name']);
        $expectation = Expectation::get();
        $user = User::select('id', 'name')->where('users.reporting_manager_id', $userRoles['id'])->get();
        $resource = User::select('id', 'name')->whereIn('id', function ($query) {
            $query->select('people_id')->from('project_manager')->where('project_id', '1')->groupBy('people_id');
        })->get();
        $metrics = FeedbackMetrics::select('metrics', 'id')->get();
        $designation = DesignationFeedbackMetric::select('navigator_designation_id', 'is_mandatory',
            'metrics_id')->get()->toArray();

        foreach ($designation as $key => $value) {
            $metricsid = $designation[$key]['metrics_id'];
            $metrics = DB::table('kra_category')->select('kra_category.id as c_id',
                'kra_category.name as category_name')->join('feedback_metrics', function ($join) use ($metricsid) {
                $join->on('kra_category.id', '=', 'feedback_metrics.category_id')
                    ->where('feedback_metrics.id', '=', $metricsid);
            })->first();
        if($metrics){
            $designation[$key]['c_id'] = $metrics->c_id;
            $designation[$key]['category_name'] = $metrics->category_name;
        }

        }
        $collection = collect($designation);
        $grouped = $collection->groupBy('category_name');
        $categorydetails = $grouped->toArray();
        $finalData = array(
            'project' => $project,
            'expectation' => $expectation,
            'user' => $user,
            'resource' => $resource,
            'metrics' => $metrics,
            'categorydetails' => $categorydetails,
        );

        return $finalData;
    }


    public function getPracticesDetails()
    {
        $roleId = Auth::user()->role_id;
        $userId = Auth::user()->id;
        $practicesData = Practices::select('practices.id', 'practices');

        if ($roleId == config('custom.practiceLeadId') || Session::get('role') == config('custom.projectManagerLead')) {
            $practicesData = $practicesData->join('practices_user', 'practices_user.practices_id', '=', 'practices.id')
                ->join('users', 'users.id', '=', 'practices_user.user_id')
                ->where('users.id', $userId);
        }
        return $practicesData->get()->toArray();

    }

    /**
     * Get ManagerName  based on  Manager Id
     * @param $managerId
     * @return managerName
     */
    public function getManagerName($managerId = null)
    {
        $managerName = User::select('users.emp_id', 'users.name', 'role_user.role_id')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id');
        $managerId = explode(',', $managerId);
        if ($managerId) {

            $managerName = $managerName->whereIn('users.id', $managerId)->groupBy('name')->get()->toArray();
            return $managerName;
        }
        return $managerName->get(['emp_id', 'name'])->toArray();
    }

    /**
     * Get ManagerId, ManagerName  based on Reporting Manager Id
     * @param $userDetails
     * @return getmanagerId
     */
    public function getManagerId($userDetails = null)
    {
        return User::join('project_manager', 'project_manager.manager_id', '=', 'users.id')
            ->where('users.reporting_manager_id', '=', $userDetails['id'])
            ->select('users.id as managerId', 'users.name as managerName')
            ->groupBy('managerName')
            ->get()->toArray();
    }

    /**
     * Get PeopleId, PeopleName  based on Reporting Manager Id
     * @param $userDetails
     * @return getmanagerId
     */
    public function getPeopleId($getProjectLeadAndManagerId, $getHierarchicalIds = [])
    {
        $getHierarchicalIds[] =  Auth::user()->id;
        $getPeopleId = User::select('users.id as id', 'users.name as name')
            ->join('role_user', 'role_user.user_id', '=', 'users.id');

        if (Session::get('role') == config('custom.projectManagerLead')) {
            $getPeopleId->where('users.id', '!=', Auth::user()->id)
                ->whereNotIn('role_user.role_id', [config('custom.DeliveryHead')]);
        }

        $getPeopleId->whereNotIn('role_id', [config('custom.adminId')]);
        if (Auth::user()->emp_id !== config('custom.PMOId') && Session::get('role') != config('custom.DeliveryHead')) {

            $getPeopleId = $getPeopleId->where('users.deleted_at', '=', null);
            if (count($getHierarchicalIds) > 0) {
                $getPeopleId->whereIn('users.reporting_manager_id',$getHierarchicalIds);
            }
        }

        if (Session::get('role') == config('custom.DeliveryHead')) {
            $getPeopleId = User::select('users.id', 'users.name')
                ->where('users.reporting_manager_id', Auth::user()->id);
        }

        $getPeopleId = $getPeopleId->groupBy('users.id')
            ->withTrashed()->get()->toArray();
        return $getPeopleId;
    }

    /**
     * @param null $roleId
     * @return mixed
     */
    public function getRoleName($roleId = null)
    {
        $managerName = User::select('name');
        if ($roleId) {
            $managerName = $managerName->where('users.role_id', '=', $roleId)->get()->first();
            return $managerName;
        }
        $managerName = $managerName->get(['name'])->toArray();
        return $managerName;
    }

    public function getRoleBasedId()
    {
        return RoleUser::select('role_id')
            ->where('role_user.user_id', Auth::user()->id)->get()->toArray();

    }

    /**
     * @param $peopleId ,$projectId
     * @return array
     */
    public function getMetricdata($peopleId, $projectId, $fromDate, $toDate, $roleId = null)
    {

        $getManagerData = [];
        $projectId = explode(',', $projectId);
        $peopleId = explode(',', $peopleId);
        $manager = PeopleFeedback::leftjoin('project', 'project.id', '=', 'people_feedback.project_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->whereIn('people_feedback.project_id', $projectId)
            ->whereIn('people_feedback.people_id', $peopleId);
        if ($fromDate && $toDate) {
            $manager = $manager->where('people_feedback.start_date', '>=', $fromDate)
                ->where('people_feedback.end_date', '<=', $toDate);
        }

        if ($roleId == config('custom.practiceLeadId') || $roleId == config('custom.projectManagersId')) {
            $manager = $manager->where('users.role_id', '=', $roleId);

        }

        $manager = $manager->select('people_feedback.manager_id', 'people_feedback.people_id', 'people_feedback.project_id', 'users.name',
            'people_feedback.id', 'people_feedback.start_date', 'people_feedback.end_date')
            ->orderBy('people_feedback.start_date')
            ->orderBy('people_feedback.manager_id')
            ->get()->toArray();
        foreach ($manager as $data) {
            $data['values'] = $this->feedbackTransactionRepository->getMetricData($data);
            $getManagerData[] = $data;

        }

        return $getManagerData;
    }


    public function getRoleIdDetails($userDetails)
    {

        return Role::leftjoin('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $userDetails['id'])
            ->select('roles.id', 'roles.name')
            ->get()->toArray();
    }

    public function getRoleIdDetailsBasedOnCurrentRole($user)
    {
        $userRole =  Role::join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id);
           // dd($user->id);exit;
            if(Session::get('role') == config('custom.practiceLeadId')){
                $userRole =  $userRole->whereNotIn('role_user.role_id', [Session::get('role')])
                    ->select('roles.id', 'roles.name')
                    ->get()->toArray();
                return $userRole;
            }
            $userRole=$userRole->whereNotIn('role_user.role_id', [Session::get('role')])
            ->select('roles.id', 'roles.name')
            ->get()->toArray();
        return $userRole;
    }

    /**
     * @param $peopleId ,$projectId,$roleId
     * @return array
     */
    public function getRoleId($peopleId, $projectId, $roleId)
    {
        $getManagerDatas = [];
        $manager = PeopleFeedback::leftjoin('project', 'project.id', '=', 'people_feedback.project_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->where('people_feedback.project_id', $projectId)
            ->where('people_feedback.people_id', $peopleId)
            ->where('users.role_id', $roleId)
            ->select('people_feedback.manager_id', 'people_feedback.people_id', 'users.name', 'users.role_id',
                'people_feedback.id')
            ->orderBy('people_feedback.start_date')
            ->get()->toArray();

        foreach ($manager as $data) {
            $data['values'] = $this->feedbackTransactionRepository->getRoleId($data);
            $getManagerDatas[] = $data;
        }

        return $getManagerDatas;
    }

    /**
     * @param $userDetails
     * @param $getManagerId
     * @return array
     */
    public function getProjectLeadAndManagerId($userDetails, $getManagerId)
    {
        $collection = collect($getManagerId);
        $managerData = $collection->pluck('managerId')->toArray();
        $user = $userDetails['id'];
        array_push($managerData, $user);
        return $managerData;
    }


    /**
     * Generate Rating Sheet in csv format
     * @param $peopleId ,$projectId,null $managerId,null $fromDate,null $toDate
     * @return array
     */
    public function getRatingDataForExportingToCsv($peopleId, $projectId, $managerId = null, $fromDate = null, $toDate = null)
    {
        $userRatingDetails = $this->getRatingDisplay($peopleId, $projectId, $managerId, $fromDate, $toDate);
        $quarter_percent = $this->getPercentageData($peopleId, $projectId, $managerId, $userRatingDetails);
        $peopleId = explode(',', $peopleId);
        $quarter = $this->feedbackTransactionRepository->getQuarterCount($peopleId, $projectId, $managerId, $fromDate, $toDate);
        list($headrow,$managers) = $this->getFinalQuarterRatingRowForExcel($quarter_percent, $quarter, $userRatingDetails);

        $rows[] = $headrow;
        $categoryId = '';
        foreach ($userRatingDetails as $userRatingDetail) {

            $row = [];
            if($userRatingDetail['category_id'] != $categoryId ){
                $row['category'] = $userRatingDetail['name'];
            }else{
                $row['category'] = "";
            }
            $categoryId = $userRatingDetail['category_id'];

            $row['Metrics Fields'] = $userRatingDetail['metrics'];
            $rating = $userRatingDetail['values'];
            for($j=0;$j< count($userRatingDetails[0]['values']);$j++){
                if(isset($rating[$j])){
                    $expectation_value = $rating[$j]['expectation_value'];
                    $comments = $rating[$j]['comments'];
                }else{
                    $expectation_value = "N/A";
                    $comments = "";
                }
                $time = strtotime($quarter[$j]);
                $month = date("F", $time);
                $year = date("Y", $time);
                $row[$managers[$j] . "'s rating for $month"."-$year"] = $expectation_value;
                $row[$managers[$j] . "'s comment for $month"."-$year"] = $comments;

            }

            $rows[] = $row;
        }

        return $rows;
    }

    function getFinalQuarterRatingRowForExcel($quarter_percent, $quarter, $userRatingDetails){
        $headrow = [];
        if($quarter_percent){
            $managers = [];
            $headrow['Category'] = " ";
            $headrow['Metrics Fields'] = "Overall Rating";
            $i=0;
            foreach ($userRatingDetails[0]['values'] as $rating) {
                $time = strtotime($rating['start_date']);
                $month = date("F", $time);
                $year = date("Y", $time);
                $headrow[$rating['name'] . "'s rating for $month"."-$year"] = ($quarter_percent[$i]) == 0 ? 'N/A':$quarter_percent[$i];
                $headrow[$rating['name'] . "'s comment for $month"."-$year"] = "";
                $managers[] = $rating['name'];
                $i++;
            }
        }
        return [$headrow,$managers];
    }

    /**
     * List fo Navigator details for Csv file
     * @param $user
     * @return array
     */
    public function getNavigatorListToCsv($user)
    {
        $navigatorList = $this->getNavigator($user);
        if ($navigatorList) {
            foreach ($navigatorList as $navigator) {
                $row = [];
                $row['Project'] = $navigator['project_name'];
                $row['Manager'] = $navigator['manager_name'];
                $row['People'] = $navigator['people_name'];
                $row['Percentage Involved'] = $navigator['percentage_involved'];
                $row['Start Date'] = $navigator['start_date'];
                $row['End Date'] = $navigator['end_date'];
                $row['Status'] = $navigator['status'];
                $rows[] = $row;
            }
            return $rows;
        }

    }


    /**
     * @param $user
     * @return mixed
     */
    public function getNavigator($user)
    {
        $userId = Auth::user()->id;
        $getHierarchicalId = $this->getReportersInHeirarchy($userId);
        $data = ProjectManager::join('users as manager', 'project_manager.manager_id', '=', 'manager.id')
            ->join('users as user', 'project_manager.people_id', '=', 'user.id')
            ->join('project', 'project_manager.project_id', '=', 'project.id');
        if (Session::get('role') != config('custom.projectManagerLead') && $user->role_id != config('custom.adminId') && Session::get('role') != config('custom.DeliveryHead')) {
            $data = $data->where('project_manager.manager_id', $user->id);
        }
        if (Session::get('role') == config('custom.projectLeadId')) {
            $data = $data->orWhereIn('project_manager.manager_id', $getHierarchicalId)
                ->orWhereIn('project_manager.people_id', $getHierarchicalId)
                ->orWhere('project_manager.manager_id', $user->id);
        }
        $data = $data->where('project_manager.deleted_at', '=', null);
        $data = $data->select('project_manager.*', 'project.name as project_name', 'manager.name as manager_name', 'user.name as people_name')
            ->get()->toArray();
        return $data;

    }

    /**
     * @param $userId
     * @return mixed
     */
    public function get($userId)
    {
        return $this->user->withTrashed()->find($userId);
    }

    /**
     * Get Reporters Details in Heirarchy
     * @param $userId , null $requiredLevel, array $reporterUserIds, null $currentLevel
     * @return array
     */
    public function getReportersInHeirarchy($userId, $requiredLevel = null, $reporterUserIds = [], $currentLevel = null)
    {
        if ($requiredLevel && $currentLevel > $requiredLevel) {
            return $reporterUserIds;
        }
        $user = $this->get($userId);
        if ($user->role_id == config('custom.adminId')) {
            return $reporterUserIds;
        }
        if ($currentLevel) {
            $reporterUserIds[] = (int)$userId;
        }
        $currentLevel++;

        foreach ($user->reporters as $reporter) {
            $reporterUserIds = $this->getReportersInHeirarchy($reporter->id, $requiredLevel, $reporterUserIds,
                $currentLevel);
        }
        return $reporterUserIds;

    }

    /**
     * Get Metrics, Comments  based on People
     * @param $peopleId , $startDate, $endDate
     * @return $resourceRatingDetails
     */
    public function getResourceRatingDetails($peopleId, $startDate, $endDate, $user)
    {
        return FeedbackTransaction::join('people_feedback', 'people_feedback.id', '=', 'feedback_transaction.people_feedback_id')
            ->leftJoin('expectation', 'expectation.id', '=', 'feedback_transaction.expectation_id')
            ->leftjoin('feedback_metrics', 'feedback_metrics.id', '=', 'feedback_transaction.feedback_metrics_id')
            ->leftJoin('kra_category', 'feedback_metrics.category_id', '=', 'kra_category.id')
            ->where('people_feedback.people_id', $peopleId)
            ->where('people_feedback.start_date', '>=', $startDate)
            ->where('people_feedback.end_date', '<=', $endDate)
            ->whereNotIn('people_feedback.manager_id', [$user->id])
            ->select('feedback_transaction.feedback_metrics_id', 'feedback_metrics.metrics', DB::Raw('ROUND(AVG(feedback_transaction.expectation_id)) as expectation_id'), DB::Raw('GROUP_CONCAT(if(feedback_transaction.comments="",null,feedback_transaction.comments)) as comments'))
            ->groupBY('feedback_transaction.feedback_metrics_id')
            ->get();
    }

    /**
     * Store People Details in Collection
     * @param $detail
     * @return array
     */
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
            $result[$i]['practicesName'] = $value->implode('practicesName', ',');
            $result[$i]['roleName'] = implode(',', array_unique(explode(',', $result[$i]['roleName'])));
            $result[$i]['practicesName'] = implode(',', array_unique(explode(',', $result[$i]['practicesName'])));
            $result[$i]['managerName'] = implode(',', array_unique(explode(',', $result[$i]['managerName'])));
            $result[$i]['projectName'] = implode(',', array_unique(explode(',', $result[$i]['projectName'])));
            $result[$i]['peopleId'] = $value->implode('peopleId', ',');
            $result[$i]['practicesId'] = $value->implode('practicesId', ',');
            $result[$i]['projectId'] = $value->implode('projectId', ',');
            $result[$i]['managerId'] = $value->implode('managerId', ',');
            $result[$i]['practicesId'] = $value->implode('practicesId', ',');
            $result[$i]['designationName'] = $value->implode('designationName', ',');
            $result[$i]['designationName'] = implode(',', array_unique(explode(',', $result[$i]['designationName'])));
            $result[$i]['designationId'] = $value->implode('designationId', ',');
            $result[$i]['recordId'] = $value->implode('recordId', ',');
            $result[$i]['start_date'] = $value->implode('start_date', ',');
            $result[$i]['start_date'] = implode(',', array_unique(explode(',', $result[$i]['start_date'])));
            $result[$i]['end_date'] = $value->implode('end_date', ',');
            $result[$i]['end_date'] = implode(',', array_unique(explode(',', $result[$i]['end_date'])));
            $result[$i]['peopleIdVal'] = $value[0]['peopleId'];
            $i++;
        }

        return $result;
    }

    public function getUserRoleName()
    {
        $currentSessionRole = Session::get('role');
        return Role::where('roles.id', '=', $currentSessionRole)->first();
    }

    /**
     * Update User Details
     * @param $userData
     * @return mixed
     */
    public function profileUpdate($userData)
    {
        return User::where('id', Auth::user()->id)->update($userData);
    }

    /**
     * Get User Practice
     * @return object
     */

    public function getUserPractice()
    {

        $practices =  User::join('practices_user', 'practices_user.user_id', '=', 'users.id')
                ->join('practices', 'practices.id', '=', 'practices_user.practices_id')
                ->select('practices.practices')
                ->where('users.id', Auth::user()->id)
                ->get();
                if(count($practices)>1){
                    $practicesResult = [];
                    foreach($practices as $practice){
                        $practicesResult[] = $practice['practices'];
                    }
                    $implodeResult = implode(",", $practicesResult);
                    return $implodeResult;
                }
                $arrayPractices =  $practices->first();
                return $arrayPractices['practices'];
    }

    /**
     * Get User Designation
     * @param $user
     * @return object
     */

    public function getUserDesignation($user)
    {
        return NavigatorDesignation::select('navigator_designations.name')
            ->join('users','users.navigator_designation_id', '=', 'navigator_designations.id')
            ->where('users.id',$user->id)->first();
    }
}
