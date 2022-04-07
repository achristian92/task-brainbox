<?php


namespace App\Repositories\Settings;


use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $table = "settings";

    protected $fillable = [
        'company', 'url_logo', 'send_email' , 'project',
        'send_overdue', 'send_credentials','notify_deadline','hours'
    ];

    public $timestamps = false;

}
