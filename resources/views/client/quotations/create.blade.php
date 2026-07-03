<x-layouts.app>
    @php
        $user = auth()->user();
        $meta = $user?->userMeta?->metadata ?? [];
    @endphp

    <x-slot:title>New Quotation - BWT</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Client Portal', 'url' => route('client.dashboard')],
        ['label' => 'My Quotations', 'url' => route('client.quotations.index')],
        ['label' => 'New Quotation'],
    ]" />

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">New Quotation</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Create a new quotation for your customer</p>
        </div>
        <span id="last-saved" class="text-xs text-gray-400"></span>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            <p class="font-semibold">Please fix {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
            <ul class="mt-2 list-disc space-y-1 [&_li]:ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="quotation-form" action="{{ route('client.quotations.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" id="form-action" name="action" value="draft">
        <input type="hidden" id="reseller_id" name="reseller_id" value="">
        <input type="hidden" id="margin_percentage_hidden" name="margin_percentage" value="0">
        <input type="hidden" id="tax_rate" name="tax_rate" value="21">

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Company Information --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Information</h2>
                </div>
                <p class="mb-4 text-sm text-gray-500 dark:text-neutral-400">Your registered business details</p>
                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                    <div class="col-span-2">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Company Name</p>
                        <p class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $meta['wholesale_company_name'] ?? $meta['company_name'] ?? '—' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Address</p>
                        <p class="mt-1 text-sm text-gray-700 dark:text-neutral-400">{{ $meta['address'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Email</p>
                        <p class="mt-1 text-sm text-gray-700 dark:text-neutral-400">{{ $meta['email'] ?? $user->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Phone</p>
                        <p class="mt-1 text-sm text-gray-700 dark:text-neutral-400">{{ $meta['phone'] ?? $user->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">VAT Number</p>
                        <p class="mt-1 text-sm text-gray-700 dark:text-neutral-400">{{ $meta['vat_number'] ?? $meta['vat'] ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Customer --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/50">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Customer</h2>
                </div>
                <p class="mb-4 text-sm text-gray-500 dark:text-neutral-400">Select the reseller customer</p>
                @if($resellers->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-neutral-400">No customers found. Add retailer users first.</p>
                @else
                    <div class="space-y-4">
                        <livewire:customer-select />
                        <div id="contact-info" class="hidden grid grid-cols-2 gap-x-6 gap-y-3 rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <div>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Contact Name</p>
                                <p id="contact-name-display" class="mt-1 text-sm font-medium text-slate-900 dark:text-white">—</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Contact Email</p>
                                <p id="contact-email-display" class="mt-1 text-sm text-gray-700 dark:text-neutral-400">—</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Contact Phone</p>
                                <p id="contact-phone-display" class="mt-1 text-sm text-gray-700 dark:text-neutral-400">—</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Quotation Info --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/50">
                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Quotation Info</h2>
                </div>
                <p class="mb-4 text-sm text-gray-500 dark:text-neutral-400">Number, dates, and reference</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-forms.input name="quotation_number" label="Quotation Number" :value="$quotationNumber" readonly />
                    <x-forms.input name="reference" label="Reference" placeholder="e.g. PO-12345" />
                    <x-forms.input name="issue_date" id="issue_date" label="Issue Date" type="date" :value="now()->format('Y-m-d')" />
                    <x-forms.input name="valid_until" label="Valid Until" type="date" />
                    <x-forms.input name="margin_percentage" label="Margin (%)" type="number" value="0" step="0.01" min="0" max="100" />
                </div>
            </div>

            {{-- Delivery --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/50">
                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Delivery</h2>
                </div>
                <p class="mb-4 text-sm text-gray-500 dark:text-neutral-400">Delivery country for VAT calculation</p>
                <x-forms.select name="delivery_country" id="delivery_country" label="Delivery Country" :options="['NL' => 'Netherlands', 'BE' => 'Belgium', 'DE' => 'Germany', 'FR' => 'France', 'IT' => 'Italy', 'ES' => 'Spain', 'GB' => 'United Kingdom', 'OTHER' => 'Other (Outside EU)']" value="NL" />
            </div>
        </div>

        {{-- Items --}}
        <livewire:items-manager />

        {{-- Notes & Terms --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-800">
                    <svg class="h-4 w-4 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Notes &amp; Terms</h2>
            </div>
            <p class="mb-4 text-sm text-gray-500 dark:text-neutral-400">Optional notes for this quotation</p>
            <x-forms.trix name="notes" />
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-end gap-3">
            <a href="{{ route('client.quotations.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                Cancel
            </a>
            <button type="submit" data-action="preview" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Preview PDF
            </button>
            <button type="submit" data-action="draft" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                Save Draft
            </button>
            <button type="submit" data-action="pdf" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                PDF
            </button>
            <button type="submit" data-action="send" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm shadow-emerald-200 transition-all hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                Send
            </button>
        </div>
    </form>

    @push('scripts')
        <script src="{{ asset('assets/js/Quantions.js') }}"></script>
    @endpush
</x-layouts.app>
