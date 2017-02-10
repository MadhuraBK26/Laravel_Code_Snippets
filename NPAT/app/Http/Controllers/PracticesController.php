<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use GridEncoder;
use Input;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Models\Practices;
use Validator;
use App\Repositories\PracticesRepository;
use Session;

class PracticesController extends Controller
{

    /**
     * Instances of User Repository
     */
    protected $practicesRepository;
    protected $userRepository;

    public function __construct(

        PracticesRepository $practicesRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct();

        $this->practicesRepository = $practicesRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the designation.
     *
     * @return \Illuminate\Http\Response
     */

    public function getPracticesData()
    {
        GridEncoder::encodeRequestedData(new \App\Repositories\PracticesRepository(), Input::all());
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $delete = true;
        $user = Auth::user();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.practices.practices')
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
        $validator = \Validator::make(request()->all(), $this->practicesRepository->validationRules,
            ['practices.regex' => \Lang::get('validation.alpha')]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $practicesName = $request->get('practices');
        $status = $request->get('status');
        $practicesData = $this->practicesRepository->storeNewPractices($practicesName, $status);
        return $practicesData;
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
        $validator = \Validator::make(request()->all(), $this->practicesRepository->validationRules,
            ['practices.regex' => \Lang::get('validation.alpha')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $practicesName = $request->get('practices');
        $status = $request->get('status');
        $designationData = $this->practicesRepository->updatePracticesDetails($id, $practicesName, $status);
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
            $result = DB::table('practices')
                ->where('practices.id', $id)
                ->update(array('practices.status' => '0'));
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Practices Deleted Successfully !'));
    }
}
