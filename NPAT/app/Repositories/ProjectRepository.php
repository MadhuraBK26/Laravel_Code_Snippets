<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use App\Models\Project;
use Auth;
use Request;
use DB;
use Session;

class ProjectRepository extends LaravelJqgridRepositoryService
{

    public $validationRules = [
        'name' => 'required|regex:/(^[A-Za-z\s\0-9]+$)+/',
        'status' => 'required'
    ];

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
	$this->Database = DB::table('project')
    ->select(DB::raw("(CASE WHEN (project.status = 1) THEN 'Active' ELSE 'InActive' END) as status"),'project.id', 'project.name','project.project_created_date', 'project.project_end_date');
      
        if (Auth::user()->role_id != config('custom.adminId') && Auth::user()->emp_id !== config('custom.PMOId') && Session::get('role') != config('custom.DeliveryHead')) {
            $userId = Auth::user()->id;
            $getHierarchicalId = $userRepository->getReportersInHeirarchy($userId);
            $this->Database->join('project_manager', 'project_manager.project_id', '=', 'project.id');
            if (Session::get('role') == config('custom.projectManagerId')) {
                $this->Database->where('project_manager.manager_id', '=', Auth::user()->id);
            } elseif (Session::get('role') == config('custom.projectLeadId')) {

                $this->Database->orWhereIn('project_manager.people_id', $getHierarchicalId);
            } elseif (Session::get('role') == config('custom.peopleId')) {
                $this->Database->where('project_manager.people_id', '=', Auth::user()->id);
            } elseif (Auth::user()->emp_id === config('custom.PMOId')) {
                $this->Database->where('project.deleted_at', '=', null);
            }
            $this->Database->where('project.deleted_at', '=', null)
                ->groupBy('project.id');
        }
        $this->visibleColumns = array('id', 'name', 'project_created_date', 'project_end_date','status');
        $this->orderBy = array(array('id', 'asc'), array('name', 'desc'));
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function getProjectDetails($projectName, $projectStatus, $projectCreatedDate, $projectEndDate)
    {
        $projectExistsOrNot = Project::select('name')
            ->where('name', $projectName)
            ->first();
        if (!$projectExistsOrNot) {
            $project = new Project();
            $project->name = $projectName;
            $project->status = $projectStatus;
            $project->project_created_date = $projectCreatedDate;
            $project->project_end_date = $projectEndDate;
            $project->save();
            return json_encode(array('success' => true, 'message' => 'Project created successfully !'));

        }
        return json_encode(array('error' => true, 'message' => 'Sorry, Project Already Exists !'));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateProjectDetails($id, $projectName, $projectStatus, $projectCreatedDate, $projectEndDate)
    {
        $project = Project::find($id);
        $project->name = $projectName;
        $project->status = $projectStatus;
        $project->project_created_date = $projectCreatedDate;
        $project->project_end_date = $projectEndDate;
        try {
            $project->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Project successfully updated!'));
    }
}