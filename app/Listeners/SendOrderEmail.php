<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderEmailRequested;
use App\Mail\OrderMail;
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
        $logFile = storage_path('logs/emaillogs.txt');
        $altLogFile = base_path('emaillogs.txt');
        $timestamp = now()->format('Y-m-d H:i:s');
        $orderNumber = $order->order_number ?? $order->id;

        if (! $to) {
            $msg = "[$timestamp] To: (none) | Order: $orderNumber | Status: failed | Error: No recipient email found\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
            return;
        }
        try {
            if (! $order->pdf_file || ! Storage::disk('public')->exists($order->pdf_file)) {
                app(\App\Services\OrderServices::class)->generateOrderPdf($order->fresh());
                $order->refresh();
            }
            // Use existing OrderMail (per request: OrderMail.php not OrderEmail.php)
            Mail::to($to)->send(new OrderMail($order));

            $msg = "[$timestamp] To: $to | Order: $orderNumber | Status: sent successfully\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
        } catch (\Throwable $e) {
            $error = str_replace(["\r", "\n"], ' ', $e->getMessage());
            $msg = "[$timestamp] To: $to | Order: $orderNumber | Status: failed | Error: $error\n";
            @file_put_contents($logFile, $msg, FILE_APPEND);
            @file_put_contents($altLogFile, $msg, FILE_APPEND);
            throw $e;
        }
    }
}
