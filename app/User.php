<?php

namespace App;

use App\Notifications\UserResetPasswordNotification;
use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Customer;
use App\Repositories\Documents\Document;
use App\Repositories\Tools\ImageableTrait;
use App\Repositories\UsersHistories\UserHistory;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, ImageableTrait, HasRoles;

    protected $fillable = [
        'name', 'last_name',
        'email', 'password',
        'status','last_login','nro_document',
        'password_plain',
        'can_be_check_all' , 'can_check_all_customers'
    ];

   /* protected $hidden = [
        'password', 'remember_token',
    ];*/

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['full_name','is_admin_or_supervisor','is_admin'];


    public function documents()
    {
        return $this->hasMany(Document::class)->orderBy('created_at','desc');
    }

    public function histories()
    {
        return $this->hasMany(UserHistory::class)->orderBy('created_at','desc');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function customers()
    {
        return $this->belongsToMany(Customer::class,'customer_user');
    }
    public function supervise()
    {
        return $this->belongsToMany(User::class,'supervisor_user','user_id','supervisor_id');
    }

    public function assigns()
    {
        return $this->belongsToMany(User::class,'supervisor_user','user_id','supervisor_id');
    }


    public function getIsAdminOrSupervisorAttribute(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function isAdminOrSupervisor(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->email === 'aruiz@tavera.pe';
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }
    public function isCollaborator(): bool
    {
        return $this->hasRole('colaborador');
    }

    public function getFullNameAttribute()
    {
        return ucwords("{$this->name} {$this->last_name}");
    }

    public function getEdit()
    {
        return route('admin.users.edit',$this->id);
    }

    public function isActive(): bool
    {
        return $this->status;
    }

    public function countAssignments()
    {
        return "-- --";
    }

    public function listBoss()
    {
        return "-- --";
    }

    public function getRouteToActive()
    {
        return route('admin.users.enable',$this->id);
    }

    public function getRouteToDesactive()
    {
        return route('admin.users.disable',$this->id);
    }

    public function getRouteToDestroy()
    {
        return route('admin.users.delete',$this->id);
    }

    public function getRouteToSendCredentials()
    {
        return route('admin.users.credentials',$this->id);
    }

    public function getRouteToLogin()
    {
        return route('login');
    }

    public function getRouteToLogout()
    {
        return route('logout');
    }
    public function getRouteToPasswordReset()
    {
        return 'password.reset';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }
}
