<?php

namespace App\Repositories\ApiRepository;

use App\Models\NavigatorCurrentCompany;
use App\Models\User;
use App\Models\NavigatorDesignation;
use App\Models\ApiClient;
use DB;
use Auth;

class UserApiRepository
{


    public function __construct()
    {

    }

    public function userApiListing($id)
    {
        if ($id == "all") {
            return User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->join('users as reportingmanager', 'reportingmanager.id', '=', 'users.reporting_manager_id')
                ->join('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id')
                ->join('practices_user', 'practices_user.user_id', '=', 'users.id')
                ->join('practices', 'practices.id', '=', 'practices_user.practices_id')
                ->select('users.name as name', 'users.emp_id as emp_id', 'users.email', 'roles.name as role_name',
                    'navigator_designations.name as designation', 'practices.practices', 'reportingmanager.name as reporting_manager')
                ->whereNotIn('role_id', [config('custom.adminId')])
                ->paginate();
        }
        return User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('users as reportingmanager', 'reportingmanager.id', '=', 'users.reporting_manager_id')
            ->join('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id')
            ->join('practices_user', 'practices_user.user_id', '=', 'users.id')
            ->join('practices', 'practices.id', '=', 'practices_user.practices_id')
            ->select('users.name as name', 'users.emp_id as emp_id', 'users.email', 'roles.name as role_name',
                'navigator_designations.name as designation', 'practices.practices', 'reportingmanager.name as reporting_manager')
            ->where('users.emp_id', $id)
            ->get();
    }

    public function userHierarchicalList()
    {
        $aa = User::select('users.*','navigator_designations.name as designationName')
            ->join('navigator_designations','navigator_designations.id', '=' ,'users.navigator_designation_id')
            ->get()->toArray();
        return $this->resultTree($aa, Auth::user()->id);

    }

    public function buildTree(array $elements, $parentId = 71)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['reporting_manager_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;

                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function resultTree(array $elements, $parentId)
    {
        $result = $this->buildTree($elements, $parentId);
        $r = [];
        foreach ($elements as $element) {
            if ($element['id'] == $parentId) {
                $element['children'] = $result;
                $r = $element;
            }
        }
        return $r;
    }

    /**
    * @param $apiKey an hash key, $userEmpId
    * @return json object as response
    */
    public function userDetailsApiWithHashKey($apiKey, $userEmpId)
    {
        $getHashKey = ApiClient::where('key', '=', $apiKey)->first();

        if ($getHashKey) {
            return $this->getResultFromQuery($getHashKey, $userEmpId, null);
        }
    }

    /**
    * @param $apiKey, $filters as an array contains various fileds
    * @return json object as response
    */
    public function getFilterResultsOnUser($apiKey, $filters)
    {
        $getHashKey = ApiClient::where('key', '=', $apiKey)->first();
        $queryfilter = "";
        $i=1;
        
        if($getHashKey){

            $queryfilter = $this->getQueryFilterFromFilters($filters);
            
            return $this->getResultFromQuery($getHashKey, null, $queryfilter);
        }
        
    }

    /*
    *   @param n number of fields and values from array
    *   To build query constraints
    */
    function getQueryFilterFromFilters($filters)
    {
        $queryfilter = "";

        foreach($filters as $key=>$value){

            if($queryfilter){
                $queryfilter .= " and ";
            }
            
            if($key == 'designation'){
                $designation = NavigatorDesignation::where('name',$value)->first();
                $key = 'navigator_designation_id';
                $value = ($designation)?$designation->id:'';
            }

            $queryfilter .= 'users.'.$key." = ";
            $queryfilter .= (gettype($value) == 'int')? $value:'"'.$value.'"';
        }
        
        return $queryfilter;
    }

    /* 
    *   To get the the result with query filter and userid
    *   UserId and $queryfilter is optional
    */
    function getResultFromQuery($getHashKey, $userEmpId=null, $queryfilter=null)
    {
        $myArray = explode(',', $getHashKey['fields']);

        $getData = User::leftjoin('navigator_current_company', 'navigator_current_company.user_id', '=', 'users.id')
                ->leftjoin('navigator_experience', 'navigator_experience.user_id', '=', 'users.id')
                ->leftjoin('navigator_personal', 'navigator_personal.user_id', '=', 'users.id')
                ->leftjoin('department', 'department.id', '=', 'navigator_current_company.department_id')
                ->leftjoin('practices_user', 'practices_user.user_id', '=', 'users.id')
                ->leftjoin('practices', 'practices.id', '=', 'practices_user.practices_id')
                ->leftjoin('practices_user as head_practices', 'head_practices.practices_id', '=', 'practices_user.practices_id')
                ->leftjoin('users as head','head.id','=', 'head_practices.user_id');

        if(in_array('reportmanagers.name as reportingmanager_name', $myArray)){
            $getData = $getData->leftjoin('navigator_designations', 'navigator_designations.id', '=', 'users.navigator_designation_id')
            ->leftjoin('users as reportmanagers','reportmanagers.id','=','users.reporting_manager_id');
        }

        
        if($userEmpId){
            $getData = $getData->where('users.emp_id', '=', $userEmpId);
        }

        $getData = $getData->where('head.navigator_designation_id', '=', config('custom.practice_head_designation_id'))
                    ->where('users.status',1)->groupBy('users.id');

        if($queryfilter){
            $getData = $getData->whereRaw($queryfilter);
        }
        if(in_array('practices', $myArray)){
            $key = array_search('practices', $myArray);
            unset($myArray[$key]);
            $getData = $getData->select($myArray)
                ->addSelect(DB::raw('GROUP_CONCAT(DISTINCT practices.practices) as practices'));

        }else{
            $getData = $getData->select($myArray);
        }
        return $getData->get()->toArray();
    }

}