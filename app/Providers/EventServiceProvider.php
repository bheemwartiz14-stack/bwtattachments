<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Events\QuotationCreated;
use App\Events\UserRegistered;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\LogProductCreated;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [SendWelcomeEmail::class],
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
