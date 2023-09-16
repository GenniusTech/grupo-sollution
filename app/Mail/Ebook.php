<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Ebook extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly array $data)
    {

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->data['fromEmail'], $this->data['fromName']),
            subject: $this->data['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'mails.ebook',
        );
    }

    public function attachments(): array
    {
        $attachmentPath = public_path('arquivos/') . $this->data['attachment'];

    if (file_exists($attachmentPath)) {
        return [
            Attachment::fromPath($attachmentPath)->as($this->data['attachment'])->withMime('application/zip')
        ];
    }

    return [];
    }
}
