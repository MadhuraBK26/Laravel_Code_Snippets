<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigatorCurrentCompany extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'navigator_current_company'; // 'emp_status',  'division',
    protected $fillable = ['user_id', 'date_of_join', 'last_working_day', 'department_id', 'division_head_id', 'probation_confirmation','ctc'];
}
