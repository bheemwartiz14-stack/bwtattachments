<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\RetailerClientInvited;
use App\Mail\RetailerClientInvitationMail;
use Illuminate\Support\Facades\Mail;

class SendRetailerClientInvitationEmailListener
{
    public function handle(RetailerClientInvited $event): void
    {
        Mail::to($event->user->email)->send(
            new RetailerClientInvitationMail($event->user, $event->password)
        );
    }
}
