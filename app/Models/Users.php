<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Illuminate\Contracts\Auth\Authenticatable;
//use Illuminate\Auth\Authenticable as AuthenticableTrait;

class Users extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'last_login',
    ];
    /**
        * Get the unique identifier for the user.
        *
        * @return mixed
        */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

//    public function roles()
//    {
//      return $this
//        ->belongsToMany('App\Role')
//        ->withTimestamps();
//    }
    public function authorizeRoles($roles) {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'This action is unauthorized.');
    }

    public function hasAnyRole($roles) {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role) {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function Role()
    {
        return $this->belongsTo('App\Models\Roles', 'role_id');
    }
//select * from `roles` where `roles`.`role_id` is null and `roles`.`role_id` is not null and `name` = ?' (length=102)
    //select * from `roles` where `roles`.`users_id` is null and `roles`.`users_id` is not null and `name` = ?' (length=104)
    public function Region()
    {
	return $this->belongsTo('App\Models\Region', 'region_id');
    }

}
