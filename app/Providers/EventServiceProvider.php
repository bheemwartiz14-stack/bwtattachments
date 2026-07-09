<?php

namespace App\Providers;

use App\Events\QuotationCreated;
use App\Events\UpdateUserMargins;
use App\Events\ContactMessageSubmitted;
use App\Events\ResellerApplicationSubmitted;
use App\Events\WelcomeOnboardingUser;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\RecalculateProductMargins;
use App\Listeners\SendContactMessageMail;
use App\Listeners\SendResellerApplicationMail;
use App\Listeners\OnboardingListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WelcomeOnboardingUser::class => [OnboardingListener::class],
        QuotationCreated::class => [GenerateQuotationPdf::class],
        ContactMessageSubmitted::class => [SendContactMessageMail::class],
        ResellerApplicationSubmitted::class => [SendResellerApplicationMail::class],
        UpdateUserMargins::class => [RecalculateProductMargins::class],
    ];
}
