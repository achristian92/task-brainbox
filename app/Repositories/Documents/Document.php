<?php


namespace App\Repositories\Documents;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Document extends Model
{
    use SearchableTrait;

    protected $fillable = [
        'user_id',
        'user_full_name',
        'type',
        'name',
        'url_file',
    ];

    protected $searchable = [
        'columns' => [
            'documents.name' => 10,
            'documents.user_full_name' => 8,
            'documents.type' => 8,
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
