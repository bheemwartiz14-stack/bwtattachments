<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Str::macro('cleanHtml', function (string $html): string {
            $html = preg_replace('/<span\s[^>]*class="ql-ui"[^>]*><\/span>/', '', $html);
            $html = preg_replace_callback('/<[^>]+>/', function ($m) {
                $tag = preg_replace('/\s+data-list="[^"]*"/', '', $m[0]);
                $tag = preg_replace('/\s+contenteditable="[^"]*"/', '', $tag);
                $tag = preg_replace('/\bql-[a-zA-Z0-9-]+\b/', '', $tag);
                $tag = preg_replace('/\s+class="\s*"/', '', $tag);
                return $tag;
            }, $html);
            $html = preg_replace('/<p(\s[^>]*)?>\s*<\/p>/', '', $html);
            $html = preg_replace('/^(\s|&nbsp;|<br\s*\/?>)+/', '', $html);
            $html = preg_replace('/(\s|&nbsp;|<br\s*\/?>)+$/', '', $html);
            $html = preg_replace('/[ \t]+/', ' ', $html);
            $html = preg_replace('/\n\s*\n\s*\n/', "\n\n", $html);
            $html = trim($html);
            return $html;
        });

        Schema::defaultStringLength(191);

        // Mail views
        View::addNamespace('mail', resource_path('views/vendor/mail'));

        // Vite build directory
        if (config('vite.enabled')) {
            Vite::useBuildDirectory(config('vite.build_directory'));
        }
        try {
            $settings = SiteSetting::pluck('value', 'key')->toArray();
            config([
                'site_settings' => $settings,
            ]);

            // Get site title
            $siteTitle = $settings['site_title'] ?? 'BWT';
        } catch (\Throwable $e) {
            // Fallback if database is unavailable
            $settings = [];
            $siteTitle = 'BWT';

            config([
                'site_settings' => [],
            ]);

            // Optional: Log the exception
            // report($e);
        }
        View::share([
            'siteTitle'    => $siteTitle,
        ]);
    }
}
