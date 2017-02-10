<?php

namespace App\Repositories;

use DB;
class FeedbackMetricsRepository
{
    public function getAllFeedbackMetricsApplicableForAUser($user_id)
    {
        return DB::table('feedback_metrics')
               ->leftJoin('designation_feedback_metric', 'designation_feedback_metric.metrics_id', '=', 'feedback_metrics.id')
               ->leftJoin('users', 'users.navigator_designation_id', '=', 'designation_feedback_metric.navigator_designation_id')
               ->where('users.id', $user_id)->get();
    }
}
