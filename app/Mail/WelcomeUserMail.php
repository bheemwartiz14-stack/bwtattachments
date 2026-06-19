<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class WelcomeUserMail extends Mailable
{
    public function __construct( public User $user,  public string $password) {}
    public function build()
    {
        return $this->subject('Welcome to the System')->view('emails.welcome-user');
    }
}