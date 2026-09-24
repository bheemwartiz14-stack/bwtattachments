<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\WelcomeOnboardingUser;
use App\Mail\OnboardingEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OnboardingListener
{
    public function handle(WelcomeOnboardingUser $event): void
    {
        $to = $event->user->email;

        if (! is_string($to) || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid email address, onboarding mail skipped', [
                'user_id' => $event->user->id,
                'to' => $to,
            ]);

            return;
        }

        try {
            Mail::to($to)->send(
                new OnboardingEmail($event->user, $event->password, $event->userType)
            );
            Log::info('Onboarding email sent', [
                'user_id' => $event->user->id,
                'to' => $to,
            ]);
        } catch (\Throwable $e) {
            Log::error('Onboarding email failed', [
                'user_id' => $event->user->id,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
