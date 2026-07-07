<footer class="border-t border-slate-200 bg-white py-6 no-print dark:border-neutral-800 dark:bg-neutral-950">
    <div class="mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-slate-500 dark:text-neutral-400">
            {!! data_get(config('site_settings'), 'footer_text', '&copy; ' . date('Y') . ' ' . config('app.name')) !!}
        </p>
    </div>
</footer>
