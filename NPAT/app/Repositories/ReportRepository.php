<?php
namespace App\Repositories;
use App\Models\FeedbackTransaction;
use App\Models\PeopleFeedback;
use App\Models\FeedbackMetrics;
use App\Models\Project;
use App\Models\User;
use Auth;
use Session;
use DB;
use App\Models\ProjectManager;

class ReportRepository
{
    /**
     * Instance for repository
     */
    protected $feedbackRepository;

    /**
     * Constructor for repository to access all the methods in repository
     */

    public function __construct(

        FeedbackTransactionRepository $feedbackTransactionRepository
    )
    {
        $this->feedbackTransactionRepository = $feedbackTransactionRepository;

    }

    /**
     * Get People list assigned to manager
     * @return array people
     */

    public function getPeople()
    {
        $userRoles = Auth::user();
        $userEmpId = User::where('users.id','=',Auth::user()->id)->get(['users.emp_id'])->first();
        if (Session::get('role') != config('custom.practiceLeadId') && Auth::user()->emp_id !== config('custom.PMOId')) {
            $people = User::join('project_manager', 'project_manager.people_id', '=', 'users.id')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->whereNotIn('role_id', [config('roles.admin')]);
            if (Auth::user()->role_id != config('custom.adminId') && Session::get('role') != config('custom.DeliveryHead')) {
                $people = $people->where('project_manager.manager_id', '=', $userRoles['id'])
                    ->where('project_manager.deleted_at', '=', null);
            }
            $people = $people->orderBy('users.name', 'asc')
                ->distinct()->withTrashed()
                ->get(['users.id', 'users.name'])->toArray();//admin
        }elseif($userEmpId->emp_id === config('custom.PMOId')){
            $people = User::where('users.reporting_manager_id','=',Auth::user()->id)->get(['users.id','users.name'])->toArray();
        }
        else {
            $people = User::select('id', 'name')->where('users.reporting_manager_id', $userRoles['id'])->get()->toArray();
        }

        $newUser = array('id' => config('custom.allUserId'), 'name' => config('custom.allUserName'));
        array_unshift($people, $newUser);
        return $people;
    }

    /**
     * Get the people name and project name
     *
     * @param  array $startdate , $enddate, $resource_id
     * @return array projectUsers
     */
    public function getProjectDetails($start_date, $end_date, $resource_id)
    {
        $userRoles = Auth::user();
        $projectUsers = Project::join('people_feedback', 'people_feedback.project_id', '=', 'project.id')
            ->leftjoin('users', 'users.id', '=', 'people_feedback.people_id')
            ->where('people_feedback.start_date', '>=', $start_date)
            ->where('people_feedback.end_date', '<=', $end_date)
            ->select('project.name as projectName',
                'users.name as peopleName',
                'people_feedback.people_id as peopleId',
                'people_feedback.project_id as projectId',
                'people_feedback.id as recordId',
                'people_feedback.start_date',
                'people_feedback.end_date'
            );

        if (!$resource_id == config('custom.allUserId')) {
            $projectUsers = $projectUsers->where('people_feedback.people_id', '=', $resource_id);
        }

        return $projectUsers->where('people_feedback.manager_id', '=', $userRoles['id'])->get()->toArray();

    }

    /**
     * Retrieving Resource Rating Details
     * @param $startdate
     * @param $enddate
     * @param $projectId
     * @return mixed
     */
    public function getResourceRatingDetails($startdate, $enddate, $projectId)
    {
        $projectUsers = Project::join('people_feedback', 'people_feedback.project_id', '=', 'project.id')
            ->leftjoin('users as manager', 'manager.id', '=', 'people_feedback.manager_id')
            ->where('people_feedback.start_date', '>=', $startdate)
            ->where('people_feedback.end_date', '<=', $enddate);
        if (!$projectId == config('custom.allUserId')) {
            $projectUsers = $projectUsers->where('people_feedback.project_id', '=', $projectId);
        }
        $projectUsers = $projectUsers->where('people_feedback.people_id', Auth::user()->id)
            ->select('project.name as projectName',
                'manager.name as managerName',
                'people_feedback.people_id as peopleId',
                'people_feedback.project_id as projectId',
                'people_feedback.manager_id as managerId',
                'people_feedback.id as id',
                'people_feedback.start_date',
                'people_feedback.end_date'
            )
            ->groupBy('projectName')
            ->get()->toArray();

        foreach ($projectUsers as $user) {
            $user['ratings'] = $this->feedbackTransactionRepository->getfeedbackMetrics($user);
        }
        return $projectUsers;


    }

    /**
     * Get the people name and project name
     *
     * @param  array $peopleId ,$projectId
     * @return ratingValue
     */
    public function ratingDetailsForPeople($peopleId, $projectId, $fromDate, $toDate)
    {
        $value = Feedbacktransaction::join('people_feedback', 'people_feedback.id', '=', 'feedback_transaction.people_feedback_id')
            ->join('feedback_metrics', 'feedback_transaction.feedback_metrics_id', '=', 'feedback_metrics.id')
            ->where('people_id', $peopleId)
            ->where('project_id', $projectId)
            ->where('feedback_transaction.start_date', '>=', $fromDate)
            ->where('feedback_transaction.end_date', '<=', $toDate)
            ->get(['expectation_id', 'comments', 'people_id', 'project_id', 'feedback_metrics.metrics']);
        $ratingValue = array('values' => $value);
        return $ratingValue;
    }

    /**
     * Get Project Data on Resource
     * @return mixed
     */
    public function getProjectDetailsBasedOnResource()
    {
        $userId = Auth::user()->id;
        $resourceProjectDetails = Project::join('project_manager', 'project_manager.project_id', '=', 'project.id')
            ->where('project_manager.people_id', $userId)
            ->get(['project.id', 'project.name as projectName'])->toArray();
        $newUser = array('id' => config('custom.allUserId'), 'projectName' => config('custom.allUserName'));
        array_unshift($resourceProjectDetails, $newUser);
        return $resourceProjectDetails;

    }

    /**
     * Get People On Summary
     * @return mixed
     */
    public function getResourceOnSummary()
    {
        $userId = Auth::user()->id;
        return User::join('people_feedback', 'people_feedback.people_id', '=', 'users.id')
            ->select('users.name', 'people_feedback.start_date', 'people_feedback.end_date')
            ->where('people_feedback.manager_id', $userId)
            ->groupBy('people_feedback.people_id')
            ->get()->toArray();
    }

    /**
     * Gives List of Months
     * @return array
     */
    public function getMonths()
    {
        $months = array();
        $currentMonth = 1;
        for ($x = $currentMonth; $x < $currentMonth + 12; $x++) {
            $months[] = date('F', mktime(0, 0, 0, $x, 1));
        }
        return $months;
    }
     
    public function getQuarterMonths()
    {
         $months = $this->getMonths();
        $quarterMonth = array('January', 'April', 'July', 'October');
        return $quarterMonth;
    }

    /**
     * Get Export Report Summary List
     * @param $fromyear ,$toyear,$peopleId,$getHierarchicalIds
     * @return array
     */
    public function getNavigatorReportSummaryListToCsv($fromyear, $toyear, $peopleId, $getHierarchicalIds)
    {

         if (Session::get('role') == config('custom.practiceLeadId')) {
        $reportSummaryData = $this->getCountOfRatingsForPracticeLead($fromyear, $toyear, $peopleId, $getHierarchicalIds);
        }
      else
        {
          $reportSummaryData = $this->getCountOfRatings($fromyear, $toyear, $peopleId, $getHierarchicalIds);
        }
        foreach ($reportSummaryData as $reportSummary) {
            $row = [];
            $row['Resource Name'] = $reportSummary['name'];
            $row['Year'] = $reportSummary['fromYear'];
            $row['Role']=$reportSummary['roleName'];
            foreach ($reportSummary['ratingMonth'] as $ratingCount) {

                $row[$ratingCount['month']] = $ratingCount['count'];
            }
            $rows[] = $row;
        }
        return $rows;
    }
    /**
     * Get Count of Reports
     * @param $fromyear
     * @return mixed
     */
    public function getCountOfRatings($fromyear, $toyear, $peopleId, $getHierarchicalIds)
    {
        $userId = Auth::user()->id;
        $data = PeopleFeedback::withTrashed()->leftjoin('users', 'people_feedback.people_id', '=', 'users.id')
            ->select('users.id', 'users.name', DB::raw('YEAR(people_feedback.start_date) as fromYear'), 'people_feedback.manager_id as manager_id');
        if (Session::get('role') != config('custom.projectManagerLead') && Session::get('role') != config('custom.adminId') && Session::get('role') != config('custom.practiceLeadId') && Session::get('role') != config('custom.DeliveryHead')) {

            $data = $data->where('people_feedback.manager_id', $userId);
        }
        if (Session::get('role') == config('custom.practiceLeadId')) {
            $data = $data->whereIn('people_feedback.people_id', $getHierarchicalIds)
                        ->where('people_feedback.manager_id', $userId);
        }
        $data = $data->whereYear('people_feedback.start_date', '>=', $fromyear)
            ->whereYear('people_feedback.end_date', '<=', $toyear);
        if (!$peopleId == config('custom.allUserId')) {
            $data = $data->where('people_feedback.people_id', '=', $peopleId);
        }
        $data = $data->distinct()
            ->get()->toArray();

        $getMonth = $this->getMonths();
        foreach ($data as $key => $user) {
            $data[$key]['ratingMonthCount'] = $this->feedbackTransactionRepository->getRatingMonth($user, $toyear);
        }
        foreach ($data as $key => $ratingdata) {
            $k = [];
            foreach ($getMonth as $datamonth) {
                $d = $ratingdata['ratingMonthCount'];
                $isFind = array_search($datamonth, array_column($d, 'month'));
                if ($isFind !== false) {
                    array_push($k, ['month' => $datamonth, 'feedback' => true, 'count' => $d[$isFind]['counting']]);
                } else {
                    array_push($k, ['month' => $datamonth, 'feedback' => false, 'count' => '']);
                }
            }
             $quaterMonth=(array_chunk($k, 3));
       
        $result=[];
        foreach ($quaterMonth as $currentMonth)
            {
                $quaterMonth=in_array(true, array_column($currentMonth,'feedback'));
                
                if($quaterMonth == true)
                {
                    array_push($result, [
                       'month' => $currentMonth[0]['month'].'-'.$currentMonth[2]['month'], 'feedback' => true, 'count' => 1]);
                }
                else
                {
                        array_push($result, [
                       'month' => $currentMonth[0]['month'].'-'.$currentMonth[2]['month'], 'feedback' => false, 'count' => '']);
                }
        }
        $data[$key]['ratingMonth'] = $result;
        } 
         $i=0;
       foreach ($data as $key )
        {
          $id=$key['manager_id'];
          $roleId = DB::table('users')           
            ->join('role_user','role_user.user_id','=','users.id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('role_user.user_id','=', $id)
            ->select('role_user.user_id','roles.id','roles.name')->first();   
            $data[$i]['role_id'] = $roleId->id;
            $data[$i]['roleName']=$roleId->name;
            $i++;
       }   
     return $data;
}

    public function getCountOfRatingsForPracticeLead($fromyear, $toyear, $peopleId, $getHierarchicalIds)
    {
        $userId = Auth::user()->id;
        $data = PeopleFeedback::withTrashed()->join('users', 'people_feedback.people_id', '=', 'users.id')
            ->select('users.id', 'users.name', DB::raw('YEAR(people_feedback.start_date) as fromYear'), DB::raw('YEAR(people_feedback.end_date) as toYear'), 'people_feedback.manager_id as manager_id');
        if (Session::get('role') != config('custom.projectManagerLead') && Session::get('role') != config('custom.adminId') &&
            Session::get('role') != config('custom.practiceLeadId') && Session::get('role') != config('custom.DeliveryHead')) {

            $data = $data->where('people_feedback.manager_id', $userId);
        }
        if (Session::get('role') == config('custom.practiceLeadId')) {
            $data = $data->whereIn('people_feedback.people_id', $getHierarchicalIds);
        }
        $data = $data->whereYear('people_feedback.start_date', '>=', $fromyear)
            ->whereYear('people_feedback.end_date', '<=', $toyear);
        if (!$peopleId == config('custom.allUserId')) {
            $data = $data->where('people_feedback.people_id', '=', $peopleId);
        }
        $data = $data->distinct()
            ->get()->toArray();

        $getMonth = $this->getMonths();
        foreach ($data as $key => $user) {
            $data[$key]['ratingMonthCount'] = $this->feedbackTransactionRepository->getRatingMonth($user, $toyear);
        }

        foreach ($data as $key => $ratingdata) {
            $k = [];
            foreach ($getMonth as $datamonth) {
                $d = $ratingdata['ratingMonthCount'];
                $isFind = array_search($datamonth, array_column($d, 'month'));
                
                if ($isFind !== false) {
                    array_push($k, ['month' => $datamonth, 'feedback' => true, 'count' => $d[$isFind]['counting']]);
                } else {
                    array_push($k, ['month' => $datamonth, 'feedback' => false, 'count' => '']);
                }
            }

            $quaterMonth=(array_chunk($k, 3));
       
        $result=[];
        foreach ($quaterMonth as $currentMonth)
            {
                $quaterMonth=in_array(true, array_column($currentMonth,'feedback'));
                
                if($quaterMonth == true)
                {
                    array_push($result, [
                       'month' => $currentMonth[0]['month'].'-'.$currentMonth[2]['month'], 'feedback' => true, 'count' => 1]);
                }
                else
                {
                        array_push($result, [
                       'month' => $currentMonth[0]['month'].'-'.$currentMonth[2]['month'], 'feedback' => false, 'count' => '']);
                }
        }
            $data[$key]['ratingMonth'] = $result;
        }

        $i=0;
       foreach ($data as $key )
        {
          $id=$key['manager_id'];
          $roleId = DB::table('users')           
            ->join('role_user','role_user.user_id','=','users.id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('role_user.user_id','=', $id)
            ->select('role_user.user_id','roles.id','roles.name')->first();   
            $data[$i]['role_id'] = $roleId->id;
            $data[$i]['roleName']=$roleId->name;
            $i++;
       }
        return $data;
    }

    /**
     * Get Sort value from Category Id
     * @return mixed
     */
    public function getSortValueFromCategoryValue($categoryId){

        return FeedbackMetrics::select('category_id','sort')->where('feedback_metrics.category_id',$categoryId)
            ->orderBy('feedback_metrics.sort')
            ->get()
            ->last();
    }

    /**
     * @param $getRowID
     * @param $getRowStatus
     * Update User Status
     * @return mixed
     */
    public function resourceStatusUpdate($getRowID, $getRowStatus)
    {
        $userStatus =  User::find($getRowID);
        $userStatus->status = ($getRowStatus == 'Active') ? 0 : 1;
        $userStatus->save();
        return $userStatus;
    }
}




