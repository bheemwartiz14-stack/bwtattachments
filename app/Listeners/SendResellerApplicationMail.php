<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ResellerApplicationSubmitted;
use App\Mail\ResellerApplicationAcknowledgementMail;
use App\Mail\ResellerApplicationMail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResellerApplicationMail
{
    public function handle(ResellerApplicationSubmitted $event): void
    {
        Log::info('SendResellerApplicationMail listener executed', [
            'application_id' => $event->application->id,
        ]);

        $this->sendTo(
            config('mail.from.admin_email'),
            new ResellerApplicationMail($event->application),
            ['application_id' => $event->application->id, 'kind' => 'admin']
        );

        $this->sendTo(
            $event->application->email,
            new ResellerApplicationAcknowledgementMail($event->application),
            ['application_id' => $event->application->id, 'kind' => 'acknowledgement']
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
            Log::warning('Invalid email address, reseller application mail skipped', [
                'to' => $to,
            ] + $context);

            return;
        }

        try {
            Mail::to($to)->send($mailable);
            Log::info('Reseller application email sent', ['to' => $to] + $context);
        } catch (\Throwable $e) {
            Log::error('Reseller application email failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ] + $context);

            throw $e;
        }
    }
}
