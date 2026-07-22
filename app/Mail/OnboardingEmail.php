<?php
declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class OnboardingEmail extends Mailable
{
    public function __construct(
        public User $user,
        public string $password,
        public string $userType
    ) {}

    public function build()
    {
        $label = match ($this->userType) {
            'wholesale' => 'Wholesale',
            'reseller'  => 'Reseller',
            'customer'  => 'Customer Client',
            default     => 'Account',
        };

        return $this->subject("Your {$label} Account Has Been Created")
            ->view('emails.user-invitation');
    }
}
