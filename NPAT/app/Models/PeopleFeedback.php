<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeopleFeedback extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'people_feedback';
    protected $fillable = ['project_id', 'manager_id', 'people_id', 'status','type', 'start_date', 'end_date'];
    public $timestamps  = false;


    public function users()
    {
        return $this->belongsTo('App\Models\User','id');
    }
    public function project()
    {
        return $this->belongsTo('App\Models\Project','id');
    }
}
