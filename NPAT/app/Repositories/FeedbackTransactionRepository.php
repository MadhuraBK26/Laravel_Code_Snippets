<?php

namespace App\Repositories;

use App\Models\FeedbackTransaction;
use App\Models\PeopleFeedback;
use App\Models\User;
use Session;
use DB;

class FeedbackTransactionRepository
{
    /**
     * @var FeedbackTransaction
     */
    private $feedbackTransaction;

    public function __construct(FeedbackTransaction $feedbackTransaction)
    {
        $this->feedbackTransaction = $feedbackTransaction;
    }

    /* Get metrics, expectation id based on project id
     * @params user
     * @return array
     */
    public function getfeedbackMetrics($user, $category_id)
    {        
        $getMetricsData = FeedbackTransaction::leftjoin('people_feedback', 'people_feedback.id', '=',
            'feedback_transaction.people_feedback_id')
            ->leftjoin('feedback_metrics', 'feedback_metrics.id', '=', 'feedback_transaction.feedback_metrics_id')                        
            ->leftjoin('designation_feedback_metric','designation_feedback_metric.metrics_id','=','feedback_metrics.id')            
            ->where('feedback_transaction.people_feedback_id', $user['id'])
            ->where('designation_feedback_metric.is_mandatory', 1)
            ->where('designation_feedback_metric.navigator_designation_id', $user['designationId']);
            
            if($category_id > 0){
                $getMetricsData = $getMetricsData->where('feedback_metrics.category_id', $category_id);
            }
            
            $getMetricsData = $getMetricsData->select(DB::Raw('sum(expectation_id) as sum, count(feedback_transaction.id) as count'))
            ->get()->toArray();
            
        return $getMetricsData;
    }

    /* Get metrics, expectation id based on project id
    * @params $peopleId,$projectId
    * @return array
    */

    public function getMetricsBasedOnProject($data, $peopleId, $projectId, $managerId, $fromDate = null, $toDate = null)
    {
        $projectId = explode(',', $projectId);
        $managerId = explode(',', $managerId);
        $data['id'] = explode(',', $data['id']);
        $getMetricsBasedOnProject = FeedbackTransaction::join('people_feedback', 'people_feedback.id', '=',
            'feedback_transaction.people_feedback_id')
            ->join('feedback_metrics','feedback_metrics.id','=','feedback_transaction.feedback_metrics_id')
            
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->Join('expectation', 'expectation.id', '=', 'feedback_transaction.expectation_id')
            ->where('feedback_transaction.feedback_metrics_id', $data['id'])
            ->where('people_feedback.type', '!=', '')
            ->whereIn('people_feedback.people_id', $peopleId)
            ->whereIn('people_feedback.project_id', $projectId)
            ->whereIn('people_feedback.manager_id', $managerId);
        if ($fromDate && $toDate) {
            $getMetricsBasedOnProject = $getMetricsBasedOnProject->where('people_feedback.start_date', '>=',
                $fromDate)
                ->where('people_feedback.end_date', '<=', $toDate);
        }
        return $getMetricsBasedOnProject->select('expectation_id',
            'feedback_transaction.feedback_metrics_id',
            'comments', 'people_feedback.start_date', 'people_feedback.end_date', 'people_feedback.people_id',
            'users.name', 'users.emp_id', 'expectation.expectation_value')
            ->orderBy('people_feedback.start_date')
            ->orderBy('people_feedback.manager_id')
            ->orderBy('feedback_metrics.id')
            ->get()->toArray();

    }

    /*
    * Getting the number quarter details for the resouces within the date range
    */
    public function getQuarterCount($peopleId, $projectId, $managerId, $fromDate = null, $toDate = null)
    {
        $projectId = explode(',', $projectId);
        $managerId = explode(',', $managerId);
        $quarter_number = PeopleFeedback::where('people_feedback.type', '!=', '')
        ->whereIn('people_feedback.people_id', $peopleId)
        ->whereIn('people_feedback.project_id', $projectId)
        ->whereIn('people_feedback.manager_id', $managerId);

        if ($fromDate && $toDate) {
            $quarter_number = $quarter_number->where('people_feedback.start_date', '>=',$fromDate)
            ->where('people_feedback.end_date', '<=', $toDate);
        }

        $quarter_dates = $quarter_number->select('people_feedback.start_date')
        ->groupBy('people_feedback.start_date')->get()->toArray();
        $quarter = [];
        foreach($quarter_dates as $date){
            $quarter[] = $date['start_date'];
        }
        
        return $quarter;
    }

    /*
    * Getting the metrics values with quarter
    */
    public function getMetricsWithQuarter($quarter, $metrics, $peopleId, $projectId, $managerId, $fromDate, $toDate)
    {
        $getMetricData = [];
        foreach ($metrics as $data) {
            $data['values'] = $this->getMetricsBasedOnProject($data, $peopleId, $projectId, $managerId, $fromDate, $toDate);

            $finalResult = [];
            $i=0;

            if($data['values']){
                foreach($data['values'] as $k=> $valueMetric){
                    $start_month = isset($valueMetric['start_date']) ? date('M', strtotime($valueMetric['start_date'])) : '';
                    $end_month = isset($valueMetric['end_date'])? date('M', strtotime($valueMetric['end_date'])) : '';
                                        
                    if($start_month != '' && $end_month != '') {
                        
                        $i = array_search($valueMetric['start_date'], $quarter);
                        $finalResult[$i] = $valueMetric;
                    } 
                    
                    $i++;
                }
            }

            $data['values'] = $finalResult;
            $getMetricData[] = $data;
        }
        return $getMetricData;
    }

    /* Get expectation id based on user id
    * @params $user
    * @return array
    */
    public function getMetricExpectationId($user)
    {
       return FeedbackTransaction::leftjoin('people_feedback', 'people_feedback.id', '=',
            'feedback_transaction.people_feedback_id')
            ->where('feedback_transaction.people_feedback_id', $user['id'])
            ->select('expectation_id')
            ->get()->toArray();
    }

    /* Get expectation id based on user id
    * @params $user
    * @return array
    */
    public function getMetricData($data)
    {
        return FeedbackTransaction::leftjoin('people_feedback', 'people_feedback.id', '=',
            'feedback_transaction.people_feedback_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->join('feedback_metrics', 'feedback_metrics.id', '=', 'feedback_transaction.feedback_metrics_id')
            ->where('feedback_transaction.people_feedback_id', $data['id'])
            ->select('feedback_transaction.feedback_metrics_id', 'feedback_metrics.metrics', 'comments', 'users.name')
            ->orderBy('feedback_metrics.category_id','ASC')
            ->orderBy('feedback_metrics.id')
            ->orderBY('people_feedback.start_date')
            ->get()->toArray();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getRoleId($data)
    {
        return FeedbackTransaction::leftjoin('people_feedback', 'people_feedback.id', '=',
            'feedback_transaction.people_feedback_id')
            ->join('users', 'users.id', '=', 'people_feedback.manager_id')
            ->join('feedback_metrics', 'feedback_metrics.id', '=', 'feedback_transaction.feedback_metrics_id')
            ->where('feedback_transaction.people_feedback_id', $data['id'])
            ->select('feedback_transaction.feedback_metrics_id', 'feedback_metrics.metrics', 'comments', 'users.name')
            ->orderBy('category_id')
            ->get()->toArray();
    }

    /**
     * Feedback Metric Data for edit
     * @param $people_feedback_id
     * @return mixed
     */
    public function getFeedbackMetricsDataForEdit($people_feedback_id)
    {
        return  $this->feedbackTransaction
            ->leftJoin('expectation', 'expectation.id', '=', 'feedback_transaction.expectation_id')
            ->leftJoin('feedback_metrics', 'feedback_metrics.id', '=', 'feedback_transaction.feedback_metrics_id')
            ->leftJoin('kra_category', 'feedback_metrics.category_id', '=', 'kra_category.id')
            ->where('people_feedback_id', $people_feedback_id)->get();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getRatingMonth($user, $toyear)
    {
        $manager = explode(',', $user['manager_id']);
        return PeopleFeedback::join('users', 'users.id', '=', 'people_feedback.people_id')
            ->where('people_feedback.people_id', $user['id'])
            ->whereIn('people_feedback.manager_id', $manager)
            ->whereYear('people_feedback.start_date', '=', $user['fromYear'])
            ->whereYear('people_feedback.end_date', '<=', $toyear)
            ->select(DB::raw('MONTHNAME(people_feedback.start_date) as month'), DB::raw('GROUP_CONCAT(MONTHNAME(people_feedback.start_date)) as similarmonth'), DB::raw('count(1) as counting'))
            ->groupBy('people_feedback.start_date')
            ->get()->toArray();
    }

}
