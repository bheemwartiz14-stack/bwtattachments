<?php

namespace App\Providers;

use App\Models\SiteSetting;
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
        try {
            $siteTitle = SiteSetting::where('key', 'site_title')->value('value') ?? 'BWT';
        } catch (\Throwable) {
            $siteTitle = 'BWT';
        }
        view()->share('siteTitle', $siteTitle);

        $settings = SiteSetting::pluck('value', 'key')->toArray();
        config(['site_settings' => $settings]);
    }
}
