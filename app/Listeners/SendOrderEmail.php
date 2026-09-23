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
        // Sanitize recipient (avoid line-break injection)
        $rawTo = $order->toUser?->email ?? \App\Models\User::role('Admin')->first()?->email;
        $to = is_string($rawTo) ? trim(str_replace(["\r", "\n"], '', $rawTo)) : $rawTo;
        if (! $to || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $fallback = \App\Models\User::role('Admin')->first()?->email;
            $fallback = is_string($fallback) ? trim(str_replace(["\r", "\n"], '', $fallback)) : $fallback;
            $to = filter_var($fallback, FILTER_VALIDATE_EMAIL) ? $fallback : null;
        }
        $orderNumber = $order->order_number ?? $order->id;

        if (! $to) {
            Log::warning("Order email skipped: no recipient email found for order {$orderNumber}.");

            return;
        }

        if (! $order->pdf_file || ! Storage::disk('public')->exists($order->pdf_file)) {
            app(\App\Services\OrderServices::class)->generateOrderPdf($order->fresh());
            $order->refresh();
        }

        // Use existing OrderMail (per request: OrderMail.php not OrderEmail.php).
        // Delivery is logged globally by App\Listeners\LogOutgoingEmail
        // to storage/logs/email.log.
        Mail::to($to)->send(new OrderMail($order));
    }
}
