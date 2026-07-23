<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactMessageSubmitted;
use App\Mail\ContactMessageAcknowledgementMail;
use App\Mail\ContactMessageMail;
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
        Mail::to(config('mail.from.address'))->send(
            new ContactMessageMail($event->contactMessage)
        );
          Log::info('Admin email sent');

        Mail::to($event->contactMessage->email)->send(
            new ContactMessageAcknowledgementMail($event->contactMessage)
        );
         Log::info('Customer acknowledgement email sent');
    }
}
