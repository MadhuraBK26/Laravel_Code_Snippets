<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Bican\Roles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract
{

    use Authenticatable,
        CanResetPassword,
        HasRoleAndPermission,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'emp_id', 'email', 'password', 'role_id', 'navigator_designation_id', 'reporting_manager_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Assigning roles for users
     */
    // protected $appends = ['role_name', 'navigator_name', 'reporting_manager_name', 'role_id'];

    public function getRoleNameAttribute()
    {

        return Role::where('id', $this->role_id)->pluck('name');
    }

    public function getNavigatorNameAttribute()
    {

        return NavigatorDesignation::where('id', $this->navigator_designation_id)->pluck('name');
    }


    public function reportingManager()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getReportingManagerNameAttribute()
    {
        return User::where('id', $this->reporting_manager_id)->pluck('name');
    }


    public function reporters()
    {
        return $this->hasMany('App\Models\User', 'reporting_manager_id');
    }

    public function getRoleIdAttribute()
    {
        return count($this->getRoles()) > 0 ? $this->getRoles()[0]->id : null;
    }

}
