<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'id');
    }
}