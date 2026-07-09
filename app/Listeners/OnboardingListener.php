<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\WelcomeOnboardingUser;
use App\Mail\OnboardingEmail;
use Illuminate\Support\Facades\Mail;

class OnboardingListener
{
    public function handle(WelcomeOnboardingUser $event): void
    {
        Mail::to($event->user->email)->send(
            new OnboardingEmail($event->user, $event->password, $event->userType)
        );
    }
}
