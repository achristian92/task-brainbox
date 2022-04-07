<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    public function showResetForm(Request $request, $token = null){
        return view('auth.passwords.reset',[
            'title' => 'Restablecer Contraseña',
            'passwordUpdateRoute' => route('password.update'),
            'token' => $token,
            'backLogin' => route('login'),
        ]);
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));
        $user->password_plain = $password;
        $user->last_login = now();
        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
}
