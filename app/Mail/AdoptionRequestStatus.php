<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdoptionRequestStatus extends Mailable
{
    use Queueable, SerializesModels;
        public $status;
        public $reason;
    /**
     * Create a new message instance.
     */
    public function __construct($status, $reason = null)
    {
         $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Adoption Request Status',
        );
    }
     public function build()
    {
        return $this->view('emails.adoption_status')
                ->with([
                    'status' => $this->status,
                    'reason' => $this->reason,
        ]);
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
