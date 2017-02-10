<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigatorExperience extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'navigator_experience';
    protected $fillable = ['user_id', 'relevent_exp', 'total_exp', 'organisation_exp', 'previous_company_name', 'previous_designation', 'previous_ctc'];
}
