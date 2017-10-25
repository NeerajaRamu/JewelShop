<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

//    public function users()
//    {
//      return $this
//        ->belongsToMany('App\User')
//        ->withTimestamps();
//    }

//    public function userRoles()
//    {
//    	return $this->hasOne('User','user_id');
//    }

//    public function users()
//    {
//        return $this->belongsTo('App\Models\Users');
//    }
    public function users()
    {
        return $this->hasMany('App\Models\Users');
    }
    
}
