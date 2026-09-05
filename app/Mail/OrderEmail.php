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

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order {$this->order->order_number} from BWT Attachments",
        );
    }

    public function content(): Content
    {
        $this->order->loadMissing([
            'items.product',
            'fromUser.userMeta',
            'toUser.userMeta',
        ]);

        return new Content(
            view: 'emails.order',
        );
    }

    public function attachments(): array
    {
        if (empty($this->order->pdf_file)) {
            return [];
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($this->order->pdf_file)) {
            return [];
        }

        $path = $disk->path($this->order->pdf_file);

        return [
            Attachment::fromPath($path)
                ->as("{$this->order->order_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
