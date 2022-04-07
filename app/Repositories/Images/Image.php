<?php


namespace App\Repositories\Images;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['url','imageable_id','imageable_type'];

    public $timestamps = false;

    public function imageable()
    {
        return $this->morphTo();
    }



}
