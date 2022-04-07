<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailActivitiesDeadline extends Mailable implements ShouldQueue
{
    use  Queueable, SerializesModels;



    private $data;
    private $setup;

    public function __construct(array $data, array $setup)
    {
        $this->data = $data;
        $this->setup = $setup;
    }


    public function build()
    {
        return $this->view('emails.users.notifyActivitiesDeadline')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject( config('mail.from.activities_deadline')." - ". $this->data['deadline'])
                    ->with([
                        'data' => $this->data,
                        'setting' => $this->setup
                    ]);
    }
}
