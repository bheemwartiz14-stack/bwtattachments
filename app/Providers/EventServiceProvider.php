<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Events\QuotationCreated;
use App\Events\RetailerClientInvited;
use App\Events\WholesaleClientsRegistered;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\LogProductCreated;
use App\Listeners\SendRetailerClientInvitationEmailListener;
use App\Listeners\SendWholesaleClientInvitationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WholesaleClientsRegistered::class => [SendWholesaleClientInvitationEmail::class],
        RetailerClientInvited::class => [SendRetailerClientInvitationEmailListener::class],
        ProductCreated::class => [LogProductCreated::class],
        QuotationCreated::class => [GenerateQuotationPdf::class],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
