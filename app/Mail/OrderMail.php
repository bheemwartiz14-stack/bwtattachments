<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order,
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order {$this->order->order_number} from BWT Attachments",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $this->order->loadMissing([
            'items.product',
            'user.userMeta',
        ]);

        return new Content(
            view: 'emails.order',
        );
    }

    /**
     * Get the message attachments.
     */
  public function attachments(): array
{
    $disk = Storage::disk('public');
    $attachments = [];
    // Order PDF
    if (!empty($this->order->pdf_file) && $disk->exists($this->order->pdf_file)) {
        $attachments[] = Attachment::fromPath(
            $disk->path($this->order->pdf_file)
        )
            ->as("{$this->order->order_number}.pdf")
            ->withMime('application/pdf');
    }
    // Order logo/file
    if (!empty($this->order->orderfilepath) && $disk->exists($this->order->orderfilepath)) {
        $attachments[] = Attachment::fromPath(
            $disk->path($this->order->orderfilepath)
        )
            ->as(basename($this->order->orderfilepath))
            ->withMime($disk->mimeType($this->order->orderfilepath));
    }
    return $attachments;
}

}
