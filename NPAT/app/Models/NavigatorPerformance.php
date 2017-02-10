<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigatorPerformance extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'navigator_performance';
    protected $fillable = ['user_id', 'interim_hike', 'rating', 'promotion', 'compensation'];
}
