<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public const WELCOME = "Witamy użytkownika";

    public function __construct(
        public string $userName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: self::WELCOME . ' ' . $this->userName,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: self::WELCOME . ' ' . $this->userName,
        );
    }
}
