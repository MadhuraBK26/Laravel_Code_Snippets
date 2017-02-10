<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Practices;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRegisterRepository;
use Illuminate\Http\Request;
use App\Services\UserService;
use Validator;
use DB;
use Auth;
use Session;
use App\Services\EncryptionService;

class RegisterController extends Controller
{

    /**
     * @var EncryptionService
     */
    private $encryptionService;

    /**
     * Instances of User Repository
     */
    protected $userRepository;
    protected $userRegisterRepository;

    /**
     * Access all methods and objects in Repository
     */
    public function __construct(
        UserRepository $userRepository,
        EncryptionService $encryptionService,
        UserService $userService,
        UserRegisterRepository $userRegisterRepository
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->userRegisterRepository = $userRegisterRepository;
        $this->encryptionService = $encryptionService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $delete = true;
        $user = Auth::user();
        $roles = $this->userRepository->getAllRoles();
        $userDetails = $this->userRepository->getRolesNameId();
        $designations = $this->userRepository->getAllDesignation();
        $practicesDetails = $this->userRepository->getPracticesDetails();
        $getRoleDetails = $this->userRepository->getRoleIdDetailsBasedOnCurrentRole($user);
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('auth.register', compact('delete'))->with([
            'roles' => $roles,
            'designations' => $designations,
            'userDetails' => $userDetails,
            'practicesDetails' => $practicesDetails,
            'getRoleDetails' => $getRoleDetails,
            'getRoleName' => $getRoleName,
        ]);
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
        $validator = \Validator::make(request()->all(), $this->userRegisterRepository->validationRules,
            ['name.regex' => \Lang::get('validation.alpha')],
            ['emp_id.regex' => \Lang::get('validation.alpha_num')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $userName = $request->get('name');
        $userEmpId = $request->get('emp_id');
        $userEmail = $request->get('email');
        $userRole = $request->get('role');
        $userPassword = bcrypt($request->get('password'));
        $userDesignation = $request->get('designation');
        $userReportingManager = $request->get('ReportingManager');
        $userPractices = $request->get('practices');
        $userIsManager = $request->get('is_manager');
        $userData = $this->userService->storeUserAndRoleDetails($userName, $userEmpId, $userEmail, $userPassword, $userRole, $userDesignation, $userReportingManager, $userPractices, $userIsManager);
        return $userData;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
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
    public function edit($id)
    {
        //
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
        $validator = \Validator::make(request()->all(), $this->userRegisterRepository->validationRules,
            ['name.regex' => \Lang::get('validation.alpha')],
            ['emp_id.regex' => \Lang::get('validation.alpha_num')]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $userPassword=!empty($request['password']) && !empty($request['password_confirmation'])? bcrypt($request->get('password')):false;
        $userName = $request->get('name');
        $userEmpId = $request->get('emp_id');
        $userEmail = $request->get('email');
        $userRole = $request->get('role');
        $userDesignation = $request->get('designation');
        $userReportingManager = $request->get('ReportingManager');
        $userPractices = $request->get('practices');
        $userIsManager = $request->get('is_manager');
        $userData = $this->userService->updateUserAndRoleDetails($id, $userName, $userEmpId, $userEmail, $userPassword, $userRole, $userDesignation, $userReportingManager, $userPractices, $userIsManager);
        return $userData;
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
            User::destroy($id);
            $user = DB::table('users')
                ->where('users.id', $id)
                ->update(array('users.status' => '0'));
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Record Deleted Successfully !'));
    }

    public function grid(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->userRegisterRepository, $request->all());
    }

    public function userPersonalDetails($id){

        $divisions = Practices::get();
        $department = Department::get();
        
        return view ('auth.register.user_details')
            ->with('department', $department)
            ->with('divisions', $divisions);
    }
}
