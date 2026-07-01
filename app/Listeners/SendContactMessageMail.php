<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactMessageSubmitted;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class SendContactMessageMail
{
    public function handle(ContactMessageSubmitted $event): void
    {
        Mail::to(config('mail.from.address'))->send(
            new ContactMessageMail($event->contactMessage)
        );
    }
}
