<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjectManager
 *
 * @author rohini
 */
class DesignationFeedbackMetric extends Model
{
    protected $table    = 'designation_feedback_metric';
    protected $fillable = ['metrics_id', 'navigator_designation_id', 'is_mandatory'];
    public $timestamps  = false;
    protected $appends  = ['metrics_name', 'navigator_designation_name', 'mandatory'];

    public function getMetricsNameAttribute()
    {
        return FeedbackMetrics::where('id', $this->metrics_id)->pluck('metrics');
    }
    public function getNavigatorDesignationNameAttribute()
    {
        return NavigatorDesignation::where('id', $this->navigator_designation_id)->pluck('name');
    }
    public function getMandatoryAttribute()
    {
        if ($this->is_mandatory) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
