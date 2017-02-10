<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GridEncoder;
use Validator;
use Auth;
use App\Models\NavigatorDesignation;
use App\Repositories\NavigatorDesignationRepository;
use Session;

class NavigatorDesignationController extends Controller
{
    /**
     * Instances of User Repository
     */
    protected $navigatorDesignationRepository;
    protected $validationRepository;
    protected $userRepository;


    public function __construct(

        NavigatorDesignationRepository $navigatorDesignationRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct();

        $this->navigatorDesignationRepository = $navigatorDesignationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the designation.
     *
     * @return \Illuminate\Http\Response
     */

    public function getNavigatorData(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->navigatorDesignationRepository, $request->all());
    }

    public function index()
    {
        $delete = true;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.designation.navigatorDesignation')
            ->with('delete', $delete)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);
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
        $validator = \Validator::make(request()->all(), $this->navigatorDesignationRepository->validationRules,
            ['name.regex' => \Lang::get('validation.designation')]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $designationName = $request->get('name');
        $status = $request->get('status');
        $designationData = $this->navigatorDesignationRepository->getNavigatorDesignationDetails($designationName, $status);
        return $designationData;
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
        $validator = \Validator::make(request()->all(), $this->navigatorDesignationRepository->validationRules,
            ['name.regex' => \Lang::get('validation.alpha')]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $designationName = $request->get('name');
        $status = $request->get('status');
        $designationData = $this->navigatorDesignationRepository->updateNavigatorDesignationDetails($id, $designationName, $status);
        return $designationData;
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
            NavigatorDesignation::destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Designation Deleted Successfully !'));
    }
}
