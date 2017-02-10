<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticesUser extends Model
{
    protected $table = 'practices_user';

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'id');
    }
}
