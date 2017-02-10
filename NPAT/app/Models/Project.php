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
class Project extends Model
{
     use \Venturecraft\Revisionable\RevisionableTrait;
     use \App\Services\Traits\RevisionableCommonTrait;
    /**
     * Table name present in databsse
     */
    use SoftDeletes;
    protected $table = 'project';

    /**
     * Field which can accept data into project table is mention here
     */

    protected $fillable = ['name', 'status', 'project_created_date', 'project_end_date'];
    protected $appends = ['revisions_link','status_text'];
    public function setProjectCreatedDateAttribute($value)
    {
        $this->attributes['project_created_date'] =date('Y-m-d', strtotime($value));
    }

    public function setProjectEndDateAttribute($value)
    {
        $this->attributes['project_end_date'] =date('Y-m-d', strtotime($value));
    }

    public function getProjectCreatedDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function getProjectEndDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }       
    public function getStatusTextAttribute()
    {
        return $this->attributes['status'] = $this->status == 1 ? 'Active' : 'InActive';
    }       
}
