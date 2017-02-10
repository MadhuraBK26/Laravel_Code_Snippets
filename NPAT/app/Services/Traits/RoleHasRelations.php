<?php

namespace App\Services\Traits;

trait RoleHasRelations
{
    use \Bican\Roles\Traits\RoleHasRelations;
    /**
     * Role belongs to many permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(config('roles.models.permission'))->withTimestamps()->withPivot('is_assigned');
    }
   
}
