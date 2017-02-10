<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeedbackMetrics;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;
use App\Repositories\MetricsRepository;
use Illuminate\Http\Request;
use Auth;
use Session;

class MetricsController extends Controller
{

    /**
     * Instances of Repository
     */
    protected $adminRepo;
    protected $userRepository;

    /**
     * Access all methods and objects in Repository
     */
    public function __construct(
        AdminRepository $adminRepo,
        UserRepository $userRepository,
        MetricsRepository $metricsRepository
    )
    {
        parent::__construct();
        $this->adminRepo = $adminRepo;
        $this->userRepository = $userRepository;
        $this->metricsRepository = $metricsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $delete = "false";
        $user = Auth::user();
        $categoryList = $this->adminRepo->getCategory();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.metrics.index', compact('categoryList', 'delete', 'getRoleDetails', 'getRoleName'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(request()->all(), $this->metricsRepository->validationRules,
            ['metrics.regex' => \Lang::get('validation.alpha')],
            ['sort.regex' => \Lang::get('validation.numeric')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $metrics = $request->get('metrics');
        $category = $request->get('category');
        $sort = $request->get('sort');
        $status = '1';
        $metricsData = $this->metricsRepository->storeNewMetrics($metrics, $category, $sort, $status);
        return $metricsData;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make(request()->all(), $this->metricsRepository->validationRules,
            ['metrics.regex' => \Lang::get('validation.alpha')],
            ['sort.regex' => \Lang::get('validation.numeric')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $metrics = $request->get('metrics');
        $category = $request->get('category');
        $sort = $request->get('sort');
        $status = '1';
        $metricsData = $this->metricsRepository->updateMetrics($id, $metrics, $category, $sort, $status);
        return $metricsData;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            FeedbackMetrics::destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric Deleted Successfully !'));
    }
}
