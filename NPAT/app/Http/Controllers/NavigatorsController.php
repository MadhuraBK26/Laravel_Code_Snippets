<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNavigatorRequest;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProjectManagerRepository;
use App\Models\ProjectManager;
use App\Repositories\NavigatorsRepository;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class NavigatorsController extends Controller
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
        ProjectManagerRepository $projectManagerRepository
    )
    {
        parent::__construct();
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->projectManagerRepository = $projectManagerRepository;
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
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        $userDetails = $this->userRepository->getRolesNameId();
        $formData = $this->adminRepo->getFormDetails();
        $navigatorsData = $this->adminRepo->showNavigatorsDetails();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        return view('admin.navigators.index', compact('formData', 'delete', 'navigatorsData', 'userDetails','getRoleDetails','getRoleName'));
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
     * Store a newly created people in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $projectName = $request->get('project');
        $projectManager = $request->get('manager');
        $projectPeople = $request->get('navigator');
        $projectStartDate = $request->get('start_date');
        $projectEndDate = $request->get('end_date');
        $projectPercentage = $request->get('percentage_involved');
        $projectAssignedStatus = $request->get('status');
        $projectData = $this->projectManagerRepository->getNavigatorDetails($projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage,$projectAssignedStatus);
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
     * Update the specified people in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $projectName = $request->get('project');
        $projectManager = $request->get('manager');
        $projectPeople = $request->get('navigator');
        $projectStartDate = $request->get('start_date');
        $projectEndDate = $request->get('end_date');
        $projectPercentage = $request->get('percentage_involved');
        $projectAssignedStatus = $request->get('status');
        $projectData = $this->projectManagerRepository->updateNavigatorDetails($id, $projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage, $projectAssignedStatus);
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
            $projectmanager = DB::table('project_manager')
                ->where('project_manager.id', $id)
                ->update(array('project_manager.status' => '0'));
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Navigator Deleted Successfully !'));
    }

    public function grid(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->projectManagerRepository, $request->all());
    }

}
