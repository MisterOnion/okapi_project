<?php
declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Lead;

class InternalLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    // create constructor instance from Lead Model
    public function __construct(public Lead $lead)
    {
        // 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // subject line for internal team
        return new Envelope(
            subject: 'New Lead Processed: ' . $this->lead->customer_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // fetch internal team blade template from view
        return new Content(
            view: 'emails.internal_lead',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
