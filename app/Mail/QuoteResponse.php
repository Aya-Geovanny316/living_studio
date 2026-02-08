<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteResponse extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Respuesta a tu cotizacion {$this->quote->quote_number}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote-response',
            with: [
                'quote' => $this->quote->loadMissing('items'),
            ],
        );
    }
}
