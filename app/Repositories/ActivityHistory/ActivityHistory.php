<?php

namespace App\Repositories\ActivityHistory;

use Illuminate\Database\Eloquent\Model;

class ActivityHistory extends Model
{
    protected $table = 'activity_histories';

    protected $fillable = [ 'activity_id', 'user', 'description', 'registered_at' ];

    public $timestamps = false;
}
