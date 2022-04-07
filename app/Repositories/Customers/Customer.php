<?php

namespace App\Repositories\Customers;

use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Presenters\CustomerPresenter;
use App\Repositories\Tools\ImageableTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Nicolaslopezj\Searchable\SearchableTrait;

class Customer extends Model
{
    use ImageableTrait, SoftDeletes, SearchableTrait;

    protected $appends = ['src_logo'];
    protected  $with = ['users','image'];
    protected $withCount = ['users'];

    protected $fillable = [
        'name','address','hours','num_transactions','status', 'ruc',
        'contact_name','contact_telephone','contact_email','notify_excess_time'
    ];

    protected $searchable = [
        'columns' => [
            'customers.name'    => 10,
            'customers.ruc'     => 8,
            'customers.address' => 5
        ]
    ];


    protected $dates = ['deleted_at'];

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getSrcLogoAttribute(): string
    {
        return $this->image ? $this->image->url : asset('img/customer-default.png');
    }

    public function searchCustomer(string $term) : Collection
    {
        return self::search($term)->get();
    }


    public function present(): CustomerPresenter
    {
        return new CustomerPresenter($this);
    }

}
