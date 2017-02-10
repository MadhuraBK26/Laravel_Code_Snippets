<?php

namespace App\Repositories;

use App\Models\DesignationFeedbackMetric;
use App\Models\FeedbackMetrics;
use App\Models\KraCategory;
use App\Models\NavigatorDesignation;
use App\Models\Practices;
use App\Models\Project;
use App\Models\ProjectManager;
use App\Models\FeedbackTransaction;
use App\Models\User;
use Auth;
use Session;
use DB;

class AdminRepository
{

    /**
     * Instance for repository
     */
    protected $feedbackTransactionRepository;

    /**
     * Constructor for repository to access all the methods in repository
     */

    public function __construct(
        FeedbackTransactionRepository $feedbackTransactionRepository
    )
    {
        $this->feedbackTransactionRepository = $feedbackTransactionRepository;
    }


    /**
     * Validation for Assigning Metric to Resource
     */
    public $validationRulesMetric = [
        'metrics' => 'required',
        'designation' => 'required',
        'mandatory' => 'required',
    ];


    /**
     * Fetch Required details for view
     * @return array complete data for view
     */
    public function getFormDetails()
    {
        $users = DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'role_id')
            ->whereNotIn('role_id', [config('custom.adminId')])
            ->where('role_user.role_id', config('custom.practiceLeadId'))
            ->orWhere('role_user.role_id', config('custom.projectManagerId'))
            ->where('users.deleted_at', '=', null)
            ->groupBy('users.id')
            ->get();
        $navigators = DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'role_id')
            ->whereNotIn('role_id', [config('custom.adminId')])
            ->where('users.deleted_at', '=', null)
            ->groupBy('users.id')
            ->get();
        $projects = Project::get(['id', 'name']);
        $completeData = array('users' => $users, 'projects' => $projects, 'navigators' => $navigators);
        return $completeData;
    }

    /**
     * Get data to display in the table from project manager
     * @return navigators data
     */
    public function showNavigatorsDetails()
    {
        $fetchNavigatorsData = ProjectManager::join('project', 'project_manager.project_id', '=', 'project.id')
            ->join('users as manager', 'project_manager.manager_id', '=', 'manager.id')
            ->join('users as people', 'project_manager.people_id', '=', 'people.id')
            ->get([
                'project.name as projectname',
                'manager.name as managername',
                'people.name as navigatorname',
                'project_manager.status',
                'project_manager.start_date',
                'project_manager.end_date',
            ]);
        if (!$fetchNavigatorsData->isEmpty()) {
            return $fetchNavigatorsData;
        }
        return false;
    }

    /**
     * Get user metrics
     * @return Metrics List
     */
    public function getuserMetrics()
    {
        $metricsList = FeedbackMetrics::get(['id', 'metrics']);
        if (!$metricsList->isEmpty()) {
            return $metricsList;
        }
        return false;
    }

    /**
     * Get project data to display in table
     * @return project details
     */
    public function getProjectData($getProjectLeadAndManagerId = null, $hierarchicalReporterIds = [])
    {
        $fetchProjectDetails = Project::select('project.id as id', 'name', 'project_created_date', 'project_end_date');
        if (Session::get('role') != config('custom.adminId') && Auth::user()->emp_id !== config('custom.PMOId') && Session::get('role') != config('custom.DeliveryHead')) {
            $fetchProjectDetails = $fetchProjectDetails->join('project_manager', 'project_manager.project_id', '=', 'project.id')->groupBy('name');
            if (Session::get('role') != config('custom.peopleId')) {
                if ($getProjectLeadAndManagerId) {
                    $fetchProjectDetails->whereIn('project_manager.manager_id', $getProjectLeadAndManagerId);
                }
            }
            if (count($hierarchicalReporterIds) > 0) {
                $fetchProjectDetails->orWhereIn('project_manager.people_id', $hierarchicalReporterIds);
                $fetchProjectDetails->orWhereIn('project_manager.manager_id', $hierarchicalReporterIds);
            }
            if (Session::get('role') == config('custom.peopleId')) {

                $fetchProjectDetails = $fetchProjectDetails->where('project_manager.people_id', '=', Auth::user()->id);
            }
        }
        $fetchProjectDetails = $fetchProjectDetails->get()->toArray();
        return $fetchProjectDetails;
    }

    /**
     * Get Practice Data 
     * @return mixed
     */
    public function getPracticeData(){

        return Practices::join('practices_user', 'practices_user.practices_id', '=', 'practices.id')
            ->join('users', 'users.id', '=', 'practices_user.user_id')
            ->select('practices.id', 'practices.practices as name', 'practices_user.user_id as user')
            ->where('users.id', Auth::user()->id)->get()->toArray();
    }

    /**
     * Get User designation details
     * @return designation list
     */
    public function getuserDesignation()
    {
        return NavigatorDesignation::get(['id', 'name']);
    }

    /**
     * Get KRA category from table
     * @return category list
     */
    public function getCategory()
    {
        return KraCategory::get(['id', 'name']);
       
    }

    /**
     * Insert metrics based on designation
     * @param array getdata
     * @return metrics values to be inserted into table
     */
    public function insertMetrics($getData)
    {
        $metric = $getData['metrics'];
        $designation = $getData['designation'];
        $mandatory = $getData['mandatory'];
        $dataToInsert = array(
            'metrics_id' => $metric,
            'navigator_designation_id' => $designation,
            'is_mandatory' => $mandatory,
        );
        return DesignationFeedbackMetric::firstOrCreate($dataToInsert);

    }
}
