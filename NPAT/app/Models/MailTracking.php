<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailTracking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table    = 'mail_tracking';
    protected $fillable = ['from_mail', 'to_mail', 'mail_counter', 'mail_purpose'];
    
}
