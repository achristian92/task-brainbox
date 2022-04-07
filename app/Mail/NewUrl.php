<?php

namespace App\Mail;

use App\Repositories\Settings\Setup;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUrl extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.new-url')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("Hola ".$this->user->name.", La URL del sistema control de actividades ha cambiado.")
            ->with([
                'user' => $this->user,
                'setting' => Setup::first()
            ]);
    }
}
