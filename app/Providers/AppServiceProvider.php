<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (config('vite.enabled')) {
            Vite::useBuildDirectory(config('vite.build_directory'));
        }
        view()->share('siteTitle', 'BWT');
    }
}
