<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        Log::info('Welcome email sent to '.$event->user->email);

        Mail::to($event->user->email)
            ->send(
                new WelcomeUserMail(
                    $event->user,
                    $event->password
                )
            );
    }
}