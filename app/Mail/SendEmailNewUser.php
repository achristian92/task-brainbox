<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailNewUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $data;
    private $setup;

    public function __construct(array $data,$setup)
    {
        $this->data = $data;
        $this->setup = $setup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.notificationEmailNewUser')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject("Bienvenido a ".strtolower($this->setup->project)." - ". $this->data['name'])
            ->with([
                'data' => $this->data,
                'setting' => $this->setup
            ]);
    }
}
