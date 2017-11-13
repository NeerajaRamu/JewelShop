<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLogs extends Model
{
    protected $table = 'access_logs';
    public    $timestamps = false;

    public function users()
    {
        return $this->belongsTo('App\Models\Users', 'user_id');
    }
    
}
