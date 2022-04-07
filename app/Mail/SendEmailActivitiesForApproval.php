<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailActivitiesForApproval extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $yesterday;
    private $data;
    private $setup;

    public function __construct(User $user, array $data,array $setup)
    {
        $this->user      = $user;
        $this->yesterday = Carbon::yesterday()->format('d/m');
        $this->data      = $data;
        $this->setup     = $setup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.notifyForApproval')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( "Actividades por Aprobar! - ". $this->yesterday)
            ->with([
                'user'      => $this->user,
                'yesterday' => $this->yesterday,
                'data'      => $this->data,
                'setting'   => $this->setup
            ]);
    }
}
