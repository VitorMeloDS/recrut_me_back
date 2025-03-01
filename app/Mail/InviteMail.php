<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $link;

    public function __construct($email, $link)
    {
        $this->email = $email;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Convite para nossa equipe!')
                    ->view('emails.invite')
                    ->with([
                        'email' => $this->email,
                        'link' => $this->link,
                    ]);
    }
}