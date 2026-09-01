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

    public function __construct(
        public Order $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order {$this->order->order_number} from BWT",
        );
    }

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

    public function attachments(): array
    {
        if (! $this->order->pdf_file) {
            return [];
        }
        $path = Storage::disk('public')->path($this->order->pdf_file);

        if (! file_exists($path)) {
            return [];
        }

        return [
            Attachment::fromPath($path)
                ->as("{$this->order->order_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
