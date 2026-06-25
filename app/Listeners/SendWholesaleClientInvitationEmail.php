<?php

namespace App\Listeners;

use App\Events\WholesaleClientsRegistered;
use App\Mail\WholesaleClientInvitationMail;
use Illuminate\Support\Facades\Mail;

class SendWholesaleClientInvitationEmail
{
    public function handle(WholesaleClientsRegistered $event): void
    {
        Mail::to($event->user->email)->send(
            new WholesaleClientInvitationMail($event->user, $event->password)
        );
    }
}
