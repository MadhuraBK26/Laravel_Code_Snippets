<?php

namespace App\Repositories;

use App\Services\LaravelJqgridRepositoryService;
use \Illuminate\Support\Facades\DB;
use App\Models\DesignationFeedbackMetric;
use Request;
use Session;
use Auth;

class AssignMetricsRepository extends LaravelJqgridRepositoryService
{
    public function __construct(\App\Services\Auth\AclAuthentication $aclAuthentication)
    {
        $aclAuthentication->can('list-of-assigned-metrics');
        $userRoles   = Auth::user();
        $this->Database = DesignationFeedbackmetric::join('feedback_metrics', 'feedback_metrics.id', '=', 'designation_feedback_metric.metrics_id')
            ->join('navigator_designations', 'navigator_designations.id', '=', 'designation_feedback_metric.navigator_designation_id');
            if(($aclAuthentication->isPermissionAssignedTo == '1') && (Session::get('role') != config('custom.DeliveryHead'))){
            	$this->Database->where('navigator_designations.id',$userRoles['navigator_designation_id']);
            }
            $this->Database->select('designation_feedback_metric.*', 'feedback_metrics.metrics as metrics_name', 'navigator_designations.name as navigator_designation_name', 'designation_feedback_metric.is_mandatory as mandatory');
        $this->visibleColumns = array('id', 'metrics_id', 'navigator_designation_id', 'is_mandatory');
        $this->orderBy = array(array('id', 'asc'));
    }
}


