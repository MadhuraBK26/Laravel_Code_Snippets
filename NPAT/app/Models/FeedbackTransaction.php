<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackTransaction extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Services\Traits\RevisionableCommonTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'feedback_transaction';
    protected $fillable = ['people_feedback_id', 'feedback_metrics_id', 'expectation_id', 'comments', 'status', 'start_date', 'end_date'];
}
