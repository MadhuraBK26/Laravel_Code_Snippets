<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KraCategory;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Auth;
use Session;

class CategoriesController extends Controller
{
    /**
     * Instances of User Repository
     */
    protected $categoryRepository;
    protected $userRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
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
        $currentSessionRole = Session::get('role');
        $getRoleName = $this->userRepository->getUserRoleName($currentSessionRole);
        return view('admin.metrics.metric_categories')
            ->with('delete', $delete)
            ->with('getRoleDetails', $getRoleDetails)
            ->with('getRoleName', $getRoleName);
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
        $input = new KraCategory();
        $input->name = $request->get('name');
        $input->id = $request->get('id');
        $input->description = $request->get('description');
        $input->sort = $request->get('sort');
        try {
            $input->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Category successfully Saved!'));
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
        $input = KraCategory::find($id);
        $input->name = $request->get('name');
        $input->id = $request->get('id');
        $input->description = $request->get('description');
        $input->sort = $request->get('sort');
        try {
            $input->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Category successfully Saved!'));
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
            KraCategory::destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Category Deleted Successfully !'));
    }

    public function grid(Request $request)
    {
        return \GridEncoder::encodeRequestedData($this->categoryRepository, $request->all());
    }

}
