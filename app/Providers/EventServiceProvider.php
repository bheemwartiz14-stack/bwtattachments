<?php

namespace App\Providers;

use App\Events\QuotationCreated;
use App\Events\UpdateUserMargins;
use App\Events\RetailerClientInvited;
use App\Events\WholesaleClientsRegistered;
use App\Events\ContactMessageSubmitted;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\SendRetailerClientInvitationEmailListener;
use App\Listeners\SendWholesaleClientInvitationEmail;
use App\Listeners\RecalculateProductMargins;
use App\Listeners\SendContactMessageMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WholesaleClientsRegistered::class => [SendWholesaleClientInvitationEmail::class],
        RetailerClientInvited::class => [SendRetailerClientInvitationEmailListener::class],
        QuotationCreated::class => [GenerateQuotationPdf::class],
        ContactMessageSubmitted::class => [SendContactMessageMail::class],
        UpdateUserMargins::class => [RecalculateProductMargins::class],
    ];
}
