<?php

namespace App\Repositories\Supervisors;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $table = 'users';

    public function users()
    {
        return $this->belongsToMany(User::class,'supervisor_user','supervisor_id','user_id');
    }

}
