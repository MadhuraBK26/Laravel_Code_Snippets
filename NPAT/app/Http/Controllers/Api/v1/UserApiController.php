<?php

namespace App\Http\Controllers\Api\v1;


Use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Repositories\ApiRepository\UserApiRepository;
use Illuminate\Http\Request;


class UserApiController extends Controller
{

    /**
     * To access user and feedback functions.
     */
    protected $userApiRepository;

    public function __construct(UserApiRepository $userApiRepository)
    {
        parent::__construct();
        $this->userApiRepository = $userApiRepository;
    }

    public function showUser($id)
    {
        $userList = $this->userApiRepository->userApiListing($id);
        return $userList;

    }

    public function showUserHierarchicalList($userid)
    {
        return $this->userApiRepository->userHierarchicalList($userid);

    }

    public function showUserDetailsBasedOnHashKey(Request $request, $userid=null) 
    {

        $apiKey =  $request->header('apiKey');
        $getDataSet = $this->userApiRepository->userDetailsApiWithHashKey($apiKey, $userid);

        if($getDataSet) {
            return response()->json($getDataSet);
        }

    }

    public function showFilterResultsOnUser(Request $request)
    {
        $filters = $request->all();
        $apiKey =  $request->header('apiKey');
        $getDataSet = $this->userApiRepository->getFilterResultsOnUser($apiKey, $filters);

        if($getDataSet) {
            return response()->json($getDataSet);
        }
    }

}
