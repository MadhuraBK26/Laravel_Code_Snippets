<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    function permissions()
    {
       return $this->hasMany(Permission::class);
    }
}
