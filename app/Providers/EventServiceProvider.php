<?php

namespace App\Providers;

use App\Events\ContactMessageSubmitted;
use App\Events\QuotationCreated;
use App\Events\ResellerApplicationSubmitted;
use App\Events\UpdateUserMargins;
use App\Events\WelcomeOnboardingUser;
use App\Listeners\GenerateQuotationPdf;
use App\Listeners\OnboardingListener;
use App\Listeners\RecalculateProductMargins;
use App\Listeners\SendContactMessageMail;
use App\Listeners\SendResellerApplicationMail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        WelcomeOnboardingUser::class => [
            OnboardingListener::class,
        ],

        QuotationCreated::class => [
            GenerateQuotationPdf::class,
        ],

        ContactMessageSubmitted::class => [
            SendContactMessageMail::class,
        ],

        ResellerApplicationSubmitted::class => [
            SendResellerApplicationMail::class,
        ],

        UpdateUserMargins::class => [
            RecalculateProductMargins::class,
        ],
    ];

    /**
     * Disable automatic event discovery.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
