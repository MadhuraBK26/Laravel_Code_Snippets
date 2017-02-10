<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class ProjectManager extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Services\Traits\RevisionableCommonTrait;
    use SoftDeletes;
    protected $table    = 'project_manager';
    protected $fillable = ['project_id', 'manager_id', 'people_id', 'status', 'start_date', 'end_date', 'percentage_involved'];

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] =date('Y-m-d', strtotime($value));
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] =date('Y-m-d', strtotime($value));
    }

    protected $appends = ['people_name', 'manager_name', 'project_name'];

    public function getPeopleNameAttribute()
    {
        return User::where('id', $this->people_id)->pluck('name');
    }
    public function getManagerNameAttribute()
    {
        return User::where('id', $this->manager_id)->pluck('name');
    }
    public function getProjectNameAttribute()
    {
        return Project::where('id', $this->project_id)->pluck('name');
    }
    public function getStartDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }
    public function getEndDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }
    public function users()
    {
        return $this->belongsTo('App\Models\User','id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project','id');
    }
}
