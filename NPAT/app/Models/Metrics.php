<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metrics extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feedback_metrics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['metrics', 'status'];
    protected $appends  = ['category_name'];
    public function getCategoryNameAttribute()
    {
        return KraCategory::where('id', $this->category_id)->pluck('name');
    }
}
