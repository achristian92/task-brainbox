<?php


namespace App\Repositories\Tools;


use App\Repositories\Images\Image;
use Carbon\Carbon;

trait ImageableTrait
{

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function urlImg()
    {
        return $this->image ? $this->image->url : asset('img/user-default.png');
    }

    public function lastLogin()
    {
        return $this->last_login ? Carbon::parse($this->last_login)->format('d/m/Y H:i')." última conexión" : "Aún no ingresa al sistema";
    }

}
