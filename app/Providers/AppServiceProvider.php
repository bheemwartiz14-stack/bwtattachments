<?php

namespace App\Providers;

use App\Livewire\PublicProductCatalog;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Livewire::component('public-product-catalog', PublicProductCatalog::class);
    }
}
