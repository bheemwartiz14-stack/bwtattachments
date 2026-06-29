<?php
declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class WholesaleClientInvitationMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $password
    ) {}

    public function build()
    {
        return $this->subject('Your Wholesale Client Account Has Been Created')
            ->view('emails.wholesale-client-invitation');
    }
}
