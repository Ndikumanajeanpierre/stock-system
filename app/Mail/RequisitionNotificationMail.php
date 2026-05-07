<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisitionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;
    public string $messageBody;
    public string $type;

    public function __construct(string $title, string $messageBody, string $type = 'info')
    {
        $this->title       = $title;
        $this->messageBody = $messageBody;
        $this->type        = $type;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
        );
    }
}