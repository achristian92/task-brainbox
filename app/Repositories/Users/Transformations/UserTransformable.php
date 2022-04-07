<?php


namespace App\Repositories\Users\Transformations;


use App\Mail\SendEmailNewUser;
use App\Repositories\Settings\Repository\SetupRepo;
use App\Repositories\UsersHistories\UserHistory;
use App\User;
use Illuminate\Support\Facades\Mail;

trait UserTransformable
{
    public function transformToListUser(User $user)
    {
        return [
            'id'               => $user->id,
            'fullNameShort'    => \Str::limit($user->full_name,20),
            'fullName'         => $user->full_name,
            'email'            => $user->email,
            'roles'            => $user['roles']->pluck('name')->implode(' | '),
            'lastLogin'        => $user->lastLogin(),
            'urlImage'         => $user->urlImg(),
            'isActive'         => $user->isActive(),
            'urlToEdit'        => $user->getEdit(),
            'urlToActive'      => $user->getRouteToActive(),
            'urlToDesactive'   => $user->getRouteToDesactive(),
            'urlToDestroy'     => $user->getRouteToDestroy(),
            'urlToCredentials' => $user->getRouteToSendCredentials(),
        ];
    }

    public function transformUserToEmail(User $user)
    {
        return [
          'name'       => $user->name,
          'email'      => $user->email,
          'password'   => $user->password_plain,
          'urlToLogin' => $user->getRouteToLogin()
        ];
    }

    public function sendEmailNewCredentials($user)
    {
        $setupRepo = resolve(SetupRepo::class);

        if ($setupRepo->sendCredentials()) {
            _addHistory(UserHistory::NOTIFY,"Envió credenciales del usuario $user->full_name");
            $transformUser = $this->transformUserToEmail($user);
            Mail::to($transformUser['email'])->send(new SendEmailNewUser($transformUser,$setupRepo->findSetup()));
        }
    }
}
