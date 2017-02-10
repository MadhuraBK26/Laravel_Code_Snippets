<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DesignationFeedbackMetric;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\AssignMetricsRepository;
use Illuminate\Http\Request;
use Auth;
use Session;

class AssignMetricsController extends Controller
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
        AssignMetricsRepository $assignMetricsRepository
    )
    {
        parent::__construct();
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->assignMetricsRepository = $assignMetricsRepository;
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
        $userDetails = $this->userRepository->getRolesNameId();
        $userMetrics = $this->adminRepo->getuserMetrics();
        $userDesignation = $this->adminRepo->getuserDesignation();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.metrics.assigns_metrics', compact('userDetails', 'userMetrics', 'userDesignation', 'delete', 'getRoleDetails', 'getRoleName'));
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
        $validator = \Validator::make($request->except('_token'), $this->adminRepo->validationRulesMetric);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $metricData = $this->adminRepo->insertMetrics($request);

        if ($metricData->exists()) {
            return json_encode(array('success' => true, 'message' => 'Metric successfully saved!'));
        } else {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
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
        $Metric = DesignationFeedbackMetric::find($id);
        $Metric->metrics_id = $request->get('metrics');
        $Metric->navigator_designation_id = $request->get('designation');
        $Metric->is_mandatory = $request->get('mandatory');
        try {
            $Metric->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric successfully updated!'));
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
            DesignationFeedbackMetric::destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metrics Deleted Successfully !'));
    }

    public function grid(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->assignMetricsRepository, $request->all());
    }
}
