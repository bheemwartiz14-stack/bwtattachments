<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderEmailRequested;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendOrderEmail
{
    public function handle(OrderEmailRequested $event): void
    {
        $order = $event->order;
        $orderNumber = $order->order_number ?? $order->id;

        $to = app(\App\Services\OrderServices::class)->resolveEmailRecipient($order);

        if (! $to) {
            Log::warning("Order email skipped: no recipient email found for order {$orderNumber}.");

            return;
        }

        if (! $order->pdf_file || ! Storage::disk('public')->exists($order->pdf_file)) {
            app(\App\Services\OrderServices::class)->generateOrderPdf($order->fresh());
            $order->refresh();
        }

        // Use existing OrderMail (per request: OrderMail.php not OrderEmail.php).
        // Sent synchronously (no queue service in use).
        // Delivery is logged globally by App\Listeners\LogOutgoingEmail
        // to storage/logs/email.log.
        try {
            Mail::to($to)->send(new OrderMail($order));
        } catch (\Throwable $e) {
            Log::error('Order mail failed', [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
