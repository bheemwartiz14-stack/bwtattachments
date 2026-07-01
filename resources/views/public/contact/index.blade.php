@php $settings = app(\App\Settings\GeneralSettings::class); @endphp

<x-layouts.public>
  <x-slot:title>Contact Us - BWT Attachment Portal</x-slot:title>

  <section class="bg-gradient-to-b from-bwtblue to-bwtblue2 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
      <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">Contact Us</h1>
      <p class="mt-4 text-lg text-blue-200 max-w-2xl mx-auto">
        Get in touch with our team for inquiries about products, pricing, or partnership opportunities.
      </p>
    </div>
  </section>

  <main class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
          <div class="bg-white rounded-xl shadow-sm p-8">
            <livewire:contact-form />
          </div>
        </div>

        <div class="space-y-6">
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Contact Information</h3>
            <div class="space-y-4 text-sm text-gray-600">
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-bwtblue mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>{{ $settings->support_email }}</span>
              </div>
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-bwtblue mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>{{ $settings->support_phone }}</span>
              </div>
              <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-bwtblue mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ $settings->address_line_1 }}{{ $settings->address_line_2 ? ', ' . $settings->address_line_2 : '' }}<br>{{ $settings->city }}{{ $settings->state ? ', ' . $settings->state : '' }} {{ $settings->pin_code }}, {{ $settings->country }}</span>
              </div>
            </div>
          </div>

          <div class="bg-bwtblue rounded-xl shadow-sm p-6 text-white">
            <h3 class="font-semibold mb-2">Reseller Program</h3>
            <p class="text-sm text-blue-200 mb-4">Interested in becoming a reseller? Contact us to learn about our partnership program.</p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-bwtblue text-sm font-semibold px-5 py-2 rounded-lg hover:bg-blue-50 transition-colors no-underline">
              Learn More
            </a>
          </div>
        </div>

      </div>
    </div>
  </main>

</x-layouts.public>
