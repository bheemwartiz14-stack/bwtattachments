<?php
declare(strict_types=1);

namespace App\Mail;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quotation $quotation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Quotation {$this->quotation->quotation_number} from BWT",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation',
        );
    }

    public function attachments(): array
    {
        $path = Storage::disk('public')->path($this->quotation->pdf_file);

        if (!file_exists($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as("{$this->quotation->quotation_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
