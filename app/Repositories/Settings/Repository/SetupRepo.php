<?php


namespace App\Repositories\Settings\Repository;


use App\Repositories\Settings\Setup;
use Prettus\Repository\Eloquent\BaseRepository;

class SetupRepo extends BaseRepository implements ISetup
{
    public function model()
    {
        return Setup::class;
    }


    public function notifyActivityOverdue(): bool
    {
        return $this->model()::first()->send_overdue && config('mail.from.send_email');
    }

    public function sendCredentials(): bool
    {
        return $this->model()::first()->send_credentials && config('mail.from.send_email');
    }

    public function notifyActivityDeadline(): bool
    {
        return $this->model()::first()->notify_deadline && config('mail.from.send_email');
    }

    public function notifyActivityAssignment(): bool
    {
        return config('mail.from.send_email');
    }

    public function findSetup(): Setup
    {
        return $this->model()::first();
    }



}
