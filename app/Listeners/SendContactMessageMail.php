<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactMessageSubmitted;
use App\Mail\ContactMessageAcknowledgementMail;
use App\Mail\ContactMessageMail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactMessageMail
{
    public function handle(ContactMessageSubmitted $event): void
    {
        Log::info('SendContactMessageMail listener executed', [
            'message_id' => $event->contactMessage->id,
            'customer_email' => $event->contactMessage->email,
        ]);

        $this->sendTo(
            config('mail.from.address'),
            new ContactMessageMail($event->contactMessage),
            ['message_id' => $event->contactMessage->id, 'kind' => 'admin']
        );

        $this->sendTo(
            $event->contactMessage->email,
            new ContactMessageAcknowledgementMail($event->contactMessage),
            ['message_id' => $event->contactMessage->id, 'kind' => 'acknowledgement']
        );
    }

    /**
     * Validate the recipient and send synchronously (no queue service
     * in use). Failures are logged; rethrows to preserve the previous
     * fail-fast behavior.
     */
    protected function sendTo(mixed $to, Mailable $mailable, array $context = []): void
    {
        if (! is_string($to) || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid email address, contact mail skipped', [
                'to' => $to,
            ] + $context);

            return;
        }

        try {
            Mail::to($to)->send($mailable);
            Log::info('Contact email sent', ['to' => $to] + $context);
        } catch (\Throwable $e) {
            Log::error('Contact email failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ] + $context);

            throw $e;
        }
    }
}
