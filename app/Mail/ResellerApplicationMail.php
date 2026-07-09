<?php
declare(strict_types=1);

namespace App\Mail;

use App\Models\ResellerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResellerApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ResellerApplication $application,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'I would like to apply for BWT Attachments reseller',
        );
    }

    public function build()
    {
        return $this->view('emails.reseller-application');
    }
}
