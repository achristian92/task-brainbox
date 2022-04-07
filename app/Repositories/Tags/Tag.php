<?php


namespace App\Repositories\Tags;


use App\Repositories\Activities\Activity;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = ['name','status','color'];

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }


}
