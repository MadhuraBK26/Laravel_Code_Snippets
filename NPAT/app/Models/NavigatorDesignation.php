<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigatorDesignation extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Services\Traits\RevisionableCommonTrait;
    protected $table = 'navigator_designations';
    protected $fillable = ['id', 'name', 'status'];

    /**
     * Mapping users with roles
     */
    public function users()
    {
        return $this->belongsToMany('\App\Models\User', 'user_navigator_designation', 'user_id', 'navigator_designation_id');
    }

    /**
     * Mapping metrics with the designation
     */
    public function metrics()
    {
        return $this->belongsToMany('\App\Models\FeedbackMetrics', 'designation_feedback_metric', 'metrics_id', 'navigator_designation_id');
    }
}
