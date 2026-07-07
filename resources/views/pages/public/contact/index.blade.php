@php $siteSettings = config('site_settings', []); @endphp

<x-layouts.public>
    <x-slot:title>Contact Us - {{ $siteTitle ?? 'BWT' }}</x-slot:title>

    <div class="bg-gray-50 min-h-screen">
        <main class="max-w-[1440px] mx-auto px-4 sm:px-8 py-12">
            {{-- Page Title --}}
            <div class="mb-14 border-b border-gray-300 pb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Contact Us</h1>
                <p class="mt-2 text-[15px] text-gray-600">Get in touch with our team for inquiries about products, pricing, or partnership opportunities.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Contact Form --}}
                <div class="lg:col-span-2">
                    <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-black/5 sm:p-12">
                        <h2 class="text-2xl font-bold text-gray-900">Send us a message</h2>
                        <p class="mt-2 text-[15px] leading-7 text-gray-600">Fill in the form below and we will get back to you as soon as possible.</p>
                        <div class="mt-8">
                            <livewire:contact-form />
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                        <h3 class="font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="space-y-4 text-sm text-gray-600">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-bwtblue">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Email</p>
                                    <a href="mailto:{{ $siteSettings['contact_email'] ?? 'info@bigworktools.com' }}" class="text-bwtblue hover:text-bwtblue2 transition-colors no-underline">{{ $siteSettings['contact_email'] ?? 'info@bigworktools.com' }}</a>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-bwtblue">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Phone</p>
                                    <a href="tel:{{ $siteSettings['contact_phone'] ?? '+1 (555) 123-4567' }}" class="text-bwtblue hover:text-bwtblue2 transition-colors no-underline">{{ $siteSettings['contact_phone'] ?? '+1 (555) 123-4567' }}</a>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-bwtblue">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Address</p>
                                    <p class="text-gray-600">{!! nl2br(e($siteSettings['site_address'] ?? '123 Business Ave, Suite 100, New York, NY 10001, USA')) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gradient-to-br from-bwtblue to-bwtblue2 p-6 shadow-sm text-white">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/15 mb-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="font-semibold mb-1">Reseller Program</h3>
                        <p class="text-sm text-blue-200 mb-4">Interested in becoming a reseller? Contact us to learn about our partnership program.</p>
                        <a href="{{ route('public.reseller-program.index') }}" class="inline-flex items-center gap-1.5 bg-white text-bwtblue text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-blue-50 transition-colors no-underline">
                            Learn More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

</x-layouts.public>
