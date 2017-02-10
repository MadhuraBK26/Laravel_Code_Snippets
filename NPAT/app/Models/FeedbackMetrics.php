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
class FeedbackMetrics extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Services\Traits\RevisionableCommonTrait;
    protected $table = 'feedback_metrics';
    protected $fillable = ['metrics', 'category_id', 'sort', 'status'];

    /**
     * Creating relation with Navigator Designation
     */
    public function navigatordesignations()
    {
        return $this->belongsToMany('\App\Models\NavigatorDesignation', 'designation_feedback_metric', 'metrics_id', 'navigator_designation_id');
    }
}
