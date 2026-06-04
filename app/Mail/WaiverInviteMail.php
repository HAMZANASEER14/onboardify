<?php
namespace App\Mail;

use App\Models\WaiverSend;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaiverInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public WaiverSend $waiverSend;

    public function __construct(WaiverSend $waiverSend)
    {
        $this->waiverSend = $waiverSend;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Please sign: ' . $this->waiverSend->waiver->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.waiver-invite',
            with: [
            'waiverSend' => $this->waiverSend, // ✅ add this
        ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}