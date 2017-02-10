<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectManager;
use Auth;
use Session;
use Request;


class ProjectManagerNavigatorsRepository extends LaravelJqgridRepositoryService
{

    public function __construct()
    {
        $userRoles = Auth::user();
        $userDetails = array('id' => $userRoles['id']);
        $q = DB::table('project_manager')
            ->join('users as manager', 'project_manager.manager_id', '=', 'manager.id')
            ->join('users as user', 'project_manager.people_id', '=', 'user.id')
            ->join('project', 'project_manager.project_id', '=', 'project.id');
        if (Session::get('role') != config('custom.projectManagerLead')) {
            $q->where('project_manager.manager_id', $userRoles['id']);
        }
        $q->select('project_manager.*', 'project.name as project_name', 'manager.name as manager_name',
            'user.name as people_name');
        $q->where('project_manager.deleted_at', '=', null);
        $this->Database = $q;
        $this->visibleColumns = array(
            'id',
            'project_id',
            'manager_id',
            'people_id',
            'start_date',
            'end_date',
            'percentage_involved'
        );
        $this->orderBy = array(array('id', 'desc'));
    }

    /**
     * Assign a navigator to created project in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function getProjectManagerNavigatorDetails(
        $projectName,
        $projectManager,
        $projectPeople,
        $projectStartDate,
        $projectEndDate,
        $projectPercentage
    )
    {
        $project = new ProjectManager();
        $project->project_id = $projectName;
        $project->manager_id = $projectManager;
        $project->people_id = $projectPeople;
        $project->start_date = $projectStartDate;
        $project->end_date = $projectEndDate;
        $project->percentage_involved = $projectPercentage;
        try {
            $project->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Navigator assigned successfully !'));

    }

    /**
     * Update the specified navigators details in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateProjectManagerNavigatorDetails(
        $id,
        $projectName,
        $projectManager,
        $projectPeople,
        $projectStartDate,
        $projectEndDate,
        $projectPercentage
    )
    {
        $project = ProjectManager::find($id);
        $project->project_id = $projectName;
        $project->manager_id = $projectManager;
        $project->people_id = $projectPeople;
        $project->start_date = $projectStartDate;
        $project->end_date = $projectEndDate;
        $project->percentage_involved = $projectPercentage;
        try {
            $project->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Navigator Updated successfully !'));
    }

}
