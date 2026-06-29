<?php
declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class RetailerClientInvitationMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $password
    ) {}

    public function build()
    {
        return $this->subject('Your Retailer Client Account Has Been Created')
            ->view('emails.retailer-client-invitation');
    }
}
