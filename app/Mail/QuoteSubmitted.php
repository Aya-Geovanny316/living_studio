<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote, public bool $isCustomer = false)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->isCustomer
            ? "Tu cotizacion {$this->quote->quote_number}"
            : "Nueva cotizacion {$this->quote->quote_number}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quote-submitted',
            with: [
                'quote' => $this->quote->loadMissing('items'),
                'isCustomer' => $this->isCustomer,
            ],
        );
    }
}
