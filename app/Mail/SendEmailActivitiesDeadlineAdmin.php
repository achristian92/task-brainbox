<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailActivitiesDeadlineAdmin extends Mailable implements ShouldQueue
{
    use  Queueable, SerializesModels;



    private $data;
    private $tomorrow;
    private $setup;
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user,array $data,array $setup)
    {
        $this->user = $user;
        $this->data = $data;
        $this->tomorrow = Carbon::tomorrow()->format('d/m');
        $this->setup = $setup;
    }


    public function build()
    {
        return $this->view('emails.users.notifyActivitiesDeadlineAdmin')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject( config('mail.from.activities_deadline')." - ". $this->tomorrow)
            ->with([
                'user'     => $this->user,
                'tomorrow' => $this->tomorrow,
                'data'     => $this->data,
                'setting'  => $this->setup
            ]);
    }
}
