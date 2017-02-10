<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNavigatorRequest;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\ProjectManagerNavigatorsRepository;
use App\Models\ProjectManager;
use App\Repositories\NavigatorsRepository;
use Illuminate\Http\Request;
use Auth;

class ProjectManagerNavigatorsController extends Controller
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
        FeedbackRepository $feedbackRepository,
        ProjectManagerNavigatorsRepository $projectManagerNavigatorsRepository
    )
    {
        parent::__construct();
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->projectManagerNavigatorsRepository = $projectManagerNavigatorsRepository;
        $this->feedbackRepository = $feedbackRepository;
    }

    /**
     * Display a listing of the people.
     *
     * @return Response
     */
    public function index()
    {
        $delete = false;
        $roleId = Auth::user()->role_id;
        $userDetails = $this->userRepository->getRolesNameId();
        $formData = $this->adminRepo->getFormDetails();
        $projectData = $this->feedbackRepository->getData();
        $navigatorsData = $this->adminRepo->showNavigatorsDetails();
        return view('admin.navigators.projectManagerNavigators', compact('formData', 'navigatorsData', 'userDetails', 'projectData', 'roleId', 'delete'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $projectName = $request->get('project');
        $projectManager = $request->get('manager');
        $projectPeople = $request->get('navigator');
        $projectStartDate = $request->get('start_date');
        $projectEndDate = $request->get('end_date');
        $projectPercentage = $request->get('percentage_involved');
        $projectData = $this->projectManagerNavigatorsRepository->getProjectManagerNavigatorDetails($projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage);
        return $projectData;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $projectName = $request->get('project');
        $projectManager = $request->get('manager');
        $projectPeople = $request->get('navigator');
        $projectStartDate = $request->get('start_date');
        $projectEndDate = $request->get('end_date');
        $projectPercentage = $request->get('percentage_involved');

        $projectData = $this->projectManagerNavigatorsRepository->updateProjectManagerNavigatorDetails($id, $projectName, $projectManager, $projectPeople, $projectStartDate, $projectEndDate, $projectPercentage);
        return $projectData;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ProjectManager::destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Navigator Deleted Successfully !'));
    }
}
