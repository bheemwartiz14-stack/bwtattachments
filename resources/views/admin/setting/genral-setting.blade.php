<x-layouts.app>
    <x-slot:title>Site Settings - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Site Settings'],
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="Site Settings" subtitle="Manage your site configuration">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </x-slot:icon>
        </x-ui.hero>

        <x-ui.error-alert />

        <form action="{{ route('admin.setting.genral-setting.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">General Settings</h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <x-forms.input
                            name="site_title"
                            label="Site Title"
                            placeholder="Enter site title"
                            :value="old('site_title', $settings['site_title'] ?? '')"
                        />

                        <x-forms.input
                            name="contact_phone"
                            label="Contact Phone"
                            placeholder="Enter contact phone number"
                            :value="old('contact_phone', $settings['contact_phone'] ?? '')"
                        />

                        <x-forms.input
                            name="contact_email"
                            label="Contact Email"
                            type="email"
                            placeholder="Enter contact email"
                            :value="old('contact_email', $settings['contact_email'] ?? '')"
                        />
                    </div>

                    <x-forms.textarea
                        name="site_address"
                        label="Site Address"
                        placeholder="Enter site address"
                        :value="old('site_address', $settings['site_address'] ?? '')"
                    />

                    <hr class="border-slate-200 dark:border-neutral-700">

                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">SEO Meta Details</h3>

                    <x-forms.textarea
                        name="meta_description"
                        label="Meta Description"
                        placeholder="Enter meta description for SEO"
                        :value="old('meta_description', $settings['meta_description'] ?? '')"
                    />

                    <x-forms.input
                        name="meta_keywords"
                        label="Meta Keywords"
                        placeholder="keyword1, keyword2, keyword3"
                        :value="old('meta_keywords', $settings['meta_keywords'] ?? '')"
                    />

                    <x-forms.input
                        name="footer_text"
                        label="Footer Text"
                        placeholder="Please added the Footer Text"
                        :value="old('footer_text', $settings['footer_text'] ?? '')"
                    />
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-8 py-5 dark:border-neutral-800 dark:bg-black/50">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
