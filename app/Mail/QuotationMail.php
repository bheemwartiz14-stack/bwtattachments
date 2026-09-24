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
    ) {
        $this->quotation->loadMissing([
            'items.product',
            'user.userMeta',
            'reseller.userMeta',
        ]);
    }

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
        if (empty($this->quotation->pdf_file)) {
            return [];
        }

        if (! Storage::disk('public')->exists($this->quotation->pdf_file)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('public', $this->quotation->pdf_file)
                ->as("{$this->quotation->quotation_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
