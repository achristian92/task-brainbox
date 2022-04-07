<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = "aruizdev27@gmail.com";
        $subject = 'This is a demo!';
        $name = 'Jane Doe';

        $headerData = [
            'category' => 'category',
            'unique_args' => [
                'variable_1' => 'abc'
            ]
        ];
        $header = $this->asString($headerData);

        $this->withSwiftMessage(function ($message) use ($header) {
            $message->getHeaders()
                ->addTextHeader('X-SMTPAPI', $header);
        });


        return $this->view('emails.test')
            ->from($address, $name)
            ->subject($subject)
            ->with([ 'test_message' => "nada" ]);
    }
    private function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

        return $json;
    }


    private function asString($data)
    {
        $json = $this->asJSON($data);

        return wordwrap($json, 76, "\n   ");
    }
}
