<?php

namespace App\Repositories;

use App\Models\DesignationFeedbackMetric;
use App\Models\Expectation;
use App\Models\FeedbackMetrics;
use App\Models\FeedbackTransaction;
use App\Models\PeopleFeedback;
use App\Models\Project;
use App\Models\ProjectManager;
use App\Models\User;
use App\Repositories\UtilityRepository;
use Auth;
use DB;
use Input;
use Session;

class FeedbackRepository
{
    /**
     * @var App\Repositories\FeedbackMetricsRepository
     */
    private $feedbackMetricsRepository;

    /**
     * Instance for repository
     */
    protected $peopleFeedback;
    protected $feedbackTransaction;
    protected $utilityRepository;

    public function __construct(\App\Models\PeopleFeedback $peopleFeedback, 
        \App\Models\ProjectManager $projectManager, 
        \App\Models\FeedbackTransaction $feedbackTransaction, 
        \App\Repositories\FeedbackMetricsRepository $feedbackMetricsRepository,
        UtilityRepository $utilityRepository
        )
    {
        $this->peopleFeedback = $peopleFeedback;
        $this->feedbackTransaction = $feedbackTransaction;
        $this->feedbackMetricsRepository = $feedbackMetricsRepository;
        $this->utilityRepository = $utilityRepository;
    }

    /**
     * to get list of all dynamic data
     * @return final data
     */
    public function getData()
    {
        $userRoles = Auth::user();
        $project = DB::table('project')
            ->select('project.id', 'project.name');
        if (Auth::user()->emp_id !== config('custom.PMOId') && Session::get('role') == config('custom.projectManagersId')) {
            $project = $project->join('project_manager', 'project_manager.project_id', '=', 'project.id')
                ->where('project_manager.manager_id', '=', $userRoles['id'])
                ->where('project_manager.deleted_at', '=', null)
                ->groupBy('project.name');
        }
        $project = $project->get();


        $expectation = Expectation::all();
        $resource = User::select('id', 'name')->whereIn('id', function ($query) {
            $query->select('people_id')->from('project_manager')->where('project_id', '1')->groupBy('people_id');
        })->get();

        $metrics = FeedbackMetrics::select('metrics', 'id')->get();
        $designation = DesignationFeedbackMetric::select('navigator_designation_id', 'is_mandatory', 'metrics_id')
            ->get()->toArray();
        foreach ($designation as $key => $value) {
            $metricsid = $designation[$key]['metrics_id'];
            $metrics = DB::table('kra_category')->select('kra_category.id as c_id', 'kra_category.name as category_name')
                ->join('feedback_metrics', function ($join) use ($metricsid) {
                    $join->on('kra_category.id', '=', 'feedback_metrics.category_id')
                        ->where('feedback_metrics.id', '=', $metricsid);
                })->first();
            if($metrics){
                $designation[$key]['c_id'] = $metrics->c_id;
                $designation[$key]['category_name'] = $metrics->category_name;
            }
        }
        $collection = collect($designation);
        $grouped = $collection->groupBy('category_name');
        $categorydetails = $grouped->toArray();
        $finalData = array(
            'project' => $project,
            'expectation' => $expectation,
            'resource' => $resource,
            'metrics' => $metrics,
            'categorydetails' => $categorydetails,
        );

        return $finalData;
    }

    /**
     * to get all data to submit
     * @return response
     */
    public function getSave($userDetails)
    {
        $start_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('start'));
        $end_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('end'));

        $metrics = FeedbackMetrics::select('id', 'metrics')->orderBy('id', 'asc')->get()->toArray();
        $feedback_data = array(
            'project_id' => Input::get('projectTitleSelect'),
            'manager_id' => $userDetails['id'],
            'people_id' => Input::get('resourceSelect'),
            'status' => config('custom.status'),
            'type'=>config('custom.status'),
            'start_date' => $start_date,
            'end_date' => $end_date
        );

        $projectStartDate = ProjectManager::select('project_manager.project_id', 'project_manager.start_date')
            ->where('project_manager.project_id', '=', $feedback_data['project_id'])
            ->first()->toArray();

        $result = PeopleFeedback::select(['people_id', 'project_id', 'start_date', 'end_date'])
            ->where('project_id', '=', Input::get('projectTitleSelect'))
            ->where('people_id', '=', Input::get('resourceSelect'))
            ->where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $end_date)
            ->first();
        if(!$result){
            $result = PeopleFeedback::select(['people_id', 'project_id', 'start_date', 'end_date'])
                ->where('project_id', '=', Input::get('projectTitleSelect'))
                ->where('people_id', '=', Input::get('resourceSelect'))
                ->where('start_date', '<=', $end_date)
                ->where('end_date', '>=', $end_date)
                ->first();

        }
        if(!$result){

            $resourceFeedback = PeopleFeedback::create($feedback_data);
            $peopleFeedback = PeopleFeedback::orderBy('id', 'desc')->first();
            $data = array(
                'people_feedback_id' => $peopleFeedback->id,
                'status' => 1,
                'start_date' => $feedback_data['start_date'],
                'end_date' => $feedback_data['end_date'],
            );
            foreach ($metrics as $metric) {
                if (Input::has('expectation_' . $metric['id']) || Input::has('comment_' . $metric['id'])) {
                    $id = $metric['id'];
                    $data['feedback_metrics_id'] = $id;
                    $data['comments'] = Input::get('comment_' . $id);
                    $data['expectation_id'] = Input::get('expectation_' . $id);
                    FeedbackTransaction::firstOrCreate($data);
                }
            }

            return $resourceFeedback;
        }
        $contractDateBegin = $start_date;
        if ($contractDateBegin >= ($result['start_date']) && ($contractDateBegin <= $result['end_date']))
        {
            return 0;
        }
    }

    /**
     * to get all data to submit
     * @return response
     */
    public function getResourceSave($userDetails)
    {
        $start_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('start'));
        $end_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('end'));

        $metrics = FeedbackMetrics::select('id', 'metrics')->orderBy('id', 'asc')->get()->toArray();
        $feedback_data = array(
            'project_id' => Input::get('practiceName'),
            'manager_id' => $userDetails['id'],
            'people_id' => Input::get('resourceName'),
            'status' => config('custom.status'),
            'type'=>config('custom.type'),
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
        $result = PeopleFeedback::select('project_id','people_id', 'start_date', 'end_date')
            ->where('project_id', '=', Input::get('practiceName'))
            ->where('people_id', '=', Input::get('resourceName'))
            ->where('manager_id','=',Auth::user()->id)
            ->where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $end_date)
            ->first();
        if (!$result) {
            $resourceFeedback = PeopleFeedback::create($feedback_data);
            $peopleFeedback = PeopleFeedback::orderBy('id', 'desc')->first();
            $data = array(
                'people_feedback_id' => $peopleFeedback->id,
                'status' => 1,
                'start_date' => $feedback_data['start_date'],
                'end_date' => $feedback_data['end_date'],
            );

            foreach ($metrics as $metric) {
                if (Input::has('expectation_' . $metric['id']) || Input::has('comment_' . $metric['id'])) {
                    $id = $metric['id'];
                    $data['feedback_metrics_id'] = $id;
                    $data['comments'] = Input::get('comment_' . $id);
                    $data['expectation_id'] = Input::get('expectation_' . $id);
                    FeedbackTransaction::firstOrCreate($data);
                }
            }
            return $resourceFeedback;
        }
    }

    /**
     * to get the resource and manager name based on the project and role
     * @return resource and manager name
     */

    public function getResourceByProjectId($project_id)
    {
        $projectMembers = ProjectManager::join('users', 'project_manager.people_id', '=', 'users.id')
            ->join('project', 'project_manager.project_id', '=', 'project.id')
            ->where('project_manager.project_id', '=', $project_id)
            ->distinct()
            ->get(['users.id', 'users.name'])->toArray();
        if (Session::get('role') == config('roles.project-manager')) {
            $managerName = ProjectManager::join('users', 'project_manager.manager_id', '=', 'users.id')
                ->join('project', 'project_manager.project_id', '=', 'project.id')
                ->where('project_manager.project_id', '=', $project_id)
                ->distinct()
                ->groupBy('users.name')
                ->get(['users.id', 'users.name'])->toArray();
            $projectMembers = array_merge($managerName, $projectMembers);
        }
        if (Auth::user()->emp_id === config('custom.PMOId')) {
            $projectMembers = User::join('project_manager', 'project_manager.manager_id', '=', 'users.id')
                ->where('project_manager.project_id', '=', $project_id)
                ->distinct()
                ->groupBy('users.name')
                ->get(['users.id', 'users.name'])->toArray();

        }
        return $projectMembers;
    }

    /**
     * Getting the form data according to the desination
     * @return final data
     */
    public function getForm($dId)
    {
        if ($designation_id = User::where('id', $dId)->pluck('navigator_designation_id')) {
            $project = Project::get();
            $expectation = Expectation::orderBy('id')->select('name', 'id')->get()->forget('1');
            $expectation  = $expectation->forget('0');
            $expectation  = $expectation->forget('3');
            $expectation  = $expectation->forget('4')->lists('name','id')->toArray();
            $expectationId = Expectation::orderBy('id')->select('name', 'id')->get()->forget('2')->lists('name','id')->toArray();
            $expectation = $expectation + $expectationId;
            $metrics = FeedbackMetrics::select('metrics', 'id')->get();
            $designation = DesignationFeedbackMetric::select('navigator_designation_id', 'is_mandatory', 'metrics_id')
                ->where('navigator_designation_id', $designation_id)->get()->toArray();
            $finalData = [];
            if($designation){
                foreach ($designation as $key => $value) {
                    $metricsid = $designation[$key]['metrics_id'];
                    $metrics = DB::table('kra_category')->select('kra_category.id as c_id', 'kra_category.name as category_name')
                        ->join('feedback_metrics', function ($join) use ($metricsid) {
                            $join->on('kra_category.id', '=', 'feedback_metrics.category_id')
                                ->where('feedback_metrics.id', '=', $metricsid);
                        })->first();
                    if($metrics){
                        $designation[$key]['c_id'] = $metrics->c_id;
                        $designation[$key]['category_name'] = $metrics->category_name;
                    }

                }
                $collection = collect($designation);
                $grouped = $collection->groupBy('category_name');
                $categorydetails = $grouped->toArray();
                $finalData = array(
                    'project' => $project,
                    'expectation' => $expectation,
                    'expectationId' => $expectationId,
                    'metrics' => $metrics,
                    'categorydetails' => $categorydetails,
                );
            }

            return $finalData;
        }
        return false;
    }

    /**
     * Updates existing feedback for a user for a project and for a particular quarter
     * @param int $peopleFeedbackId Primary id of people_feedback table / PeopleFeedback model
     */
    public function updateProjectPerformanceFeedback($peopleFeedbackId)
    {
        $start_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('start'));
        $end_date = $this->utilityRepository->getDateFormat("Y-m-d", Input::get('end'));

        PeopleFeedback::where('id', $peopleFeedbackId)->update(array('start_date' => $start_date, 'end_date' => $end_date));
        $feedbackMetrices = $this->feedbackMetricsRepository->getAllFeedbackMetricsApplicableForAUser(PeopleFeedback::find($peopleFeedbackId)->people_id);
        foreach ($feedbackMetrices as $feedbackMetrice) {
            $metrics_id = $feedbackMetrice->metrics_id;
            $feedbackTransaction = $this->getFeedbackTransactionObject($peopleFeedbackId, $metrics_id);
            if (!$feedbackTransaction) {
                $feedbackTransaction = new FeedbackTransaction();
                $feedbackTransaction->people_feedback_id = $peopleFeedbackId;
                $feedbackTransaction->feedback_metrics_id = $metrics_id;
                $feedbackTransaction->status = 1;
                $feedbackTransaction->start_date = $start_date;
                $feedbackTransaction->end_date = $end_date;
            }
            if (Input::get('expectation_' . $metrics_id) > 0) {
                $feedbackTransaction->comments = Input::get('comment_' . $metrics_id);
                $feedbackTransaction->expectation_id = Input::get('expectation_' . $metrics_id);
                $feedbackTransaction->save();
            }
        }
    }

    /**
     * Gets the object of FeedbackTransaction based on PeopleFeedback
     * @param  int $peopleFeedbackId Primary id of people_feedback table / PeopleFeedback model
     * @return \Illuminate\Database\Eloquent\Collection FeedbackTransaction
     */
    public function getFeedbackTransactionObjects($peopleFeedbackId)
    {
        return $this->feedbackTransaction->where('people_feedback_id', $peopleFeedbackId)->get();
    }

    public function getFeedbackTransactionObject($peopleFeedbackId, $feedbackMetricsId)
    {
        return $this->feedbackTransaction
            ->where('people_feedback_id', $peopleFeedbackId)
            ->where('feedback_metrics_id', $feedbackMetricsId)
            ->first();
    }
}
