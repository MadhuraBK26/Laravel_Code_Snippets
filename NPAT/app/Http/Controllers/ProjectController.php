<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;
use App\Models\Project;
use Validator;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class ProjectController extends Controller
{
    /**
     * Instances of User Repository
     */
    protected $userRepository;
    /**
     * Instances of Admin Repository
     */
    protected $adminRepo;

    /**
     * Access all methods and objects in Repository
     */
    public function __construct(
        AdminRepository $adminRepo,
        UserRepository $userRepository,
        ProjectRepository $projectRepository
    )
    {
        parent::__construct();
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Display a listing of the people.
     *
     * @return Response
     */
    public function index()
    {
        $delete = true;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole =Session::get('role');
        $getRoleName=$this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.project.index', compact('delete','getRoleDetails','getRoleName'));
    }

    /**
     * Show the form for creating a new people.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(request()->all(), $this->projectRepository->validationRules,
            ['name.regex' => \Lang::get('validation.project')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $projectName = $request->get('name');
        $projectStatus = $request->get('status');
        $projectCreatedDate = $request->get('project_created_date');
        $projectEndDate = $request->get('project_end_date');
        $projectData = $this->projectRepository->getProjectDetails($projectName, $projectStatus, $projectCreatedDate, $projectEndDate);
        return $projectData;
    }

    /**
     * Display the specified people.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified people.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified project in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(request()->all(), $this->projectRepository->validationRules,
            ['name.regex' => \Lang::get('validation.alpha')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $projectName = $request->get('name');
        $projectStatus = $request->get('status');
        $projectCreatedDate = $request->get('project_created_date');
        $projectEndDate = $request->get('project_end_date');
        $projectData = $this->projectRepository->updateProjectDetails($id, $projectName, $projectStatus, $projectCreatedDate, $projectEndDate);
        return $projectData;
    }

    /**
     * Remove the specified people from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Project::destroy($id);
            $project = DB::table('project')
                ->where('project.id', $id)
                ->update(array('project.status' => '0'));

        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Project Deleted Successfully !'));
    }
    
    public function grid(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->projectRepository, $request->all());
    }
}
