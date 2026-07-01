<?php

namespace App\Providers;

use App\Events\UpdatedProductMarginForAllUsersByProduct;
use App\Events\QuotationCreated;
use App\Events\RetailerClientInvited;
use App\Events\UpdateProductMarginByUser;
use App\Events\WholesaleClientsRegistered;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\DispatchProductPricingSync;
use App\Listeners\LogProductCreated;
use App\Listeners\SendRetailerClientInvitationEmailListener;
use App\Listeners\DispatchMarginUpdateJob;
use App\Listeners\SendWholesaleClientInvitationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WholesaleClientsRegistered::class => [SendWholesaleClientInvitationEmail::class],
        UpdateProductMarginByUser::class => [DispatchMarginUpdateJob::class],
        RetailerClientInvited::class => [SendRetailerClientInvitationEmailListener::class],
        UpdatedProductMarginForAllUsersByProduct::class => [
            DispatchProductPricingSync::class,
        ],
        QuotationCreated::class => [GenerateQuotationPdf::class],
        \App\Events\ContactMessageSubmitted::class => [\App\Listeners\SendContactMessageMail::class],
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
