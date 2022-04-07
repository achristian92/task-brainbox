<?php


namespace App\Repositories\UsersHistories;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class UserHistory extends Model
{
    use SearchableTrait;

    protected $table = "user_history";

    protected $fillable = [
        'user_id',
        'user_full_name',
        'type',
        'description',
        'model'
    ];

    CONST SESSION = 'session';
    CONST UPDATED = 'actualizar';
    CONST STORE = 'guardar';
    CONST DELETE = 'eliminar';
    CONST DISABLE = 'disactivar';
    CONST ENABLE = 'activar';
    CONST NOTIFY = 'notificar';
    CONST EXPORT = 'exportar';
    CONST IMPORT = 'importar';
    CONST RESET = 'resetear';
    CONST REVERSE = 'revertir';
    CONST APPROVED = 'aprobar';
    CONST DUPLICATE = 'duplicar';

    protected $searchable = [
        'columns' => [
            'user_history.description' => 10,
            'user_history.user_full_name' => 8,
            'user_history.type' => 8,
        ],
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
