<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\ProjectManager;
use Auth;
use Session;
use Request;


class ProjectManagerRepository extends LaravelJqgridRepositoryService
{

    protected $userRepository;

    public function __construct(\App\Services\Auth\AclAuthentication $aclAuthentication, UserRepository $userRepository)
    {
        $aclAuthentication->can(config('custom.navigatorSlug'));
        $userRoles = Auth::user();
        $userId = Auth::user()->id;
        $getHierarchicalId = $userRepository->getReportersInHeirarchy($userId);
        $this->Database = DB::table('project_manager')
            ->select('project_manager.*',DB::raw("(CASE WHEN (project_manager.status = 1) THEN 'Active' 
                WHEN (project_manager.status = 2) THEN 'InActive'
                WHEN (project_manager.status = 3) THEN 'Released' END) as status"), 'project.name as project_name', 'manager.name as manager_name', 'user.name as people_name')
            ->join('users as manager', 'project_manager.manager_id', '=', 'manager.id')
            ->join('users as user', 'project_manager.people_id', '=', 'user.id');
        if (Session::get('role') == config('custom.projectManagerId') && Auth::user()->emp_id !== config('custom.PMOId')) {
            $this->Database->where('project_manager.manager_id', $userRoles['id']);
        } elseif (Session::get('role') == config('custom.projectLeadId')) {
            $this->Database->orWhereIn('project_manager.manager_id', $getHierarchicalId)
                ->orWhereIn('project_manager.people_id', $getHierarchicalId)
                ->orWhere('project_manager.manager_id', $userRoles['id']);
        } elseif (Session::get('role') == config('custom.peopleId')) {
            $this->Database->where('project_manager.people_id', $userRoles['id']);
        } elseif (Auth::user()->emp_id === config('custom.PMOId')) {
            $this->Database->where('project_manager.deleted_at', '=', null);
        }
        if ($aclAuthentication->isPermissionAssignedTo == '1') {
            $this->Database->where('project_manager.people_id', $userRoles['id']);
        }
        $this->Database->join('project', 'project_manager.project_id', '=', 'project.id');
        $this->visibleColumns = array('id', 'project_id', 'manager_id', 'people_id', 'start_date', 'end_date', 'percentage_involved','status');
        $this->orderBy = array(array('id', 'asc'));
    }

    /**
     * Assign a navigator to created project in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function getNavigatorDetails($projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage, $projectAssignedStatus)
    {
        $navigatorExistsOrNot = ProjectManager::select('people_id', 'project_id', 'manager_id')
            ->where('people_id', $projectPeople)
            ->where('manager_id', $projectManager)
            ->where('project_id', $projectName)
            ->first();
        if (!$navigatorExistsOrNot) {
            $project = new ProjectManager();
            $project->project_id = $projectName;
            $project->manager_id = $projectManager;
            $project->people_id = $projectPeople;
            $project->start_date = $projectStartDate;
            $project->end_date = $projectEndDate;
            $project->percentage_involved = $projectPercentage;
            $project->status = $projectAssignedStatus;
            $project->save();
            return json_encode(array('success' => true, 'message' => 'Navigator assigned successfully !'));
        }
        return json_encode(array('error' => true, 'message' => 'Sorry, Navigator Already Assigned !'));
    }

    /**
     * Update the specified navigators details in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public
    function updateNavigatorDetails($id, $projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage, $projectAssignedStatus)
    {
        $project = ProjectManager::find($id);
        $project->project_id = $projectName;
        $project->manager_id = $projectManager;
        $project->people_id = $projectPeople;
        $project->start_date = $projectStartDate;
        $project->end_date = $projectEndDate;
        $project->percentage_involved = $projectPercentage;
        $project->status = $projectAssignedStatus;
        try {
            $project->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Navigator Updated successfully !'));
    }

}
