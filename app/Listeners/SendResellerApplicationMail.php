<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\ResellerApplicationSubmitted;
use App\Mail\ResellerApplicationAcknowledgementMail;
use App\Mail\ResellerApplicationMail;
use Illuminate\Support\Facades\Mail;

class SendResellerApplicationMail
{
    public function handle(ResellerApplicationSubmitted $event): void
    {
        Mail::to(config('mail.from.admin_email'))->send(
            new ResellerApplicationMail($event->application)
        );

        Mail::to($event->application->email)->send(
            new ResellerApplicationAcknowledgementMail($event->application)
        );
    }
}
