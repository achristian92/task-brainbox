<?php


namespace App\Repositories\SubActivities;


use App\Repositories\Activities\Activity;
use Illuminate\Database\Eloquent\Model;

class SubActivity extends Model
{
    protected $fillable = ['activity_id', 'name', 'duration', 'completed_at'];

    public $timestamps = false;

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
