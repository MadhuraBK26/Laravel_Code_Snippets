<?php
namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\NavigatorDesignation;
use Request;
use Session;
use Auth;


class NavigatorDesignationRepository extends LaravelJqgridRepositoryService
{

    public $validationRules = [
        'name' => 'required|regex:/(^[A-Za-z\s]+$)+/',
        'status' => 'required',
    ];

    public function __construct(\App\Services\Auth\AclAuthentication $aclAuthentication)

    {
        $aclAuthentication->can('list-designation');
        $userRoles   = Auth::user();
        $this->Database = DB::table('navigator_designations');
        if(Auth::user()->role_id != config('custom.adminId') && Auth::user()->emp_id !== config('custom.PMOId') && Session::get('role') != config('custom.DeliveryHead')){
            $this->Database->join('users', 'users.navigator_designation_id', '=', 'navigator_designations.id')
            ->where('users.id', $userRoles['id']);
        }
            $this->Database->select('navigator_designations.*',DB::raw("(CASE WHEN (navigator_designations.status = 1) THEN 'Active' ELSE 'InActive' END) as status"));
        $this->visibleColumns = array('id', 'name', 'status');
        $this->orderBy = array(array('id', 'asc'));
    }


  /**
   * Create navigator designation.
   *
   * @param  Request $request
   * @return Response
   */
    public function getNavigatorDesignationDetails($designationName, $status)
    {
        $designation = new NavigatorDesignation();
        $designation->name = $designationName;
        $designation->status = $status;
        try {
            $designation->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Navigator Designation Created successfully !'));

    }

    /**
     * Update the specified navigators designation details in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function updateNavigatorDesignationDetails($id, $designationName, $status)
    {
        $designation = NavigatorDesignation::find($id);
        $designation->name = $designationName;
        $designation->status = $status;
        try {
            $designation->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }
        return json_encode(array('success' => true, 'message' => 'Navigator Designation Updated successfully !'));
    }


}