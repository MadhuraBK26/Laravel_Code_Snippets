<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class NavigatorPersonal extends Model
{
   
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'navigator_personal';
    protected $fillable = ['user_id', 'father_name', 'marital_status', 'date_of_birth', 'residential_address', 'present_address', 'mobile_number', 'landline', 'personal_email', 'pan_number', 'aadhar_number'];

    public function getViewDateFormat($inputDate){

        return Carbon::parse($inputDate)->format('m/d/Y');
    }
}
