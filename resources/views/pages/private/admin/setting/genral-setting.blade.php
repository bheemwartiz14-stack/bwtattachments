<x-layouts.app>
    <x-slot:title>Site Settings - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin Portal', 'url' => route('admin.dashboard')],
        ['label' => 'Site Settings'],
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="Site Settings" icon="heroicon-o-cog-6-tooth" />

       

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
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                    <x-ui.button type="submit" variant="primary" label="Save Settings" />
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>
