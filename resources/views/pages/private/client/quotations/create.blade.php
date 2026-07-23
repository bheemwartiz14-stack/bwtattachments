<x-layouts.app>
    @push('styles')
        <link href="{{ asset('assets/css/Quantions.css') }}" rel="stylesheet">
    @endpush
    @php
        $user = auth()->user();
        $meta = $user?->userMeta?->metadata ?? [];

    @endphp


    <x-slot:title>New Quotation - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Wholesaler Portal', 'url' => route('client.dashboard')],
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

    @if ($errors->any())
        <div
            class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            <p class="font-semibold">Please fix
                {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
            <ul class="mt-2 list-disc space-y-1 [&_li]:ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="quotation-form" action="{{ route('client.quotations.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" id="form-action" name="action" value="draft">
        <input type="hidden" id="reseller_id" name="reseller_id" value="{{ old('reseller_id') }}">
        <input type="hidden" id="items-json" name="items" value="">
        <input type="hidden" id="margin_percentage_hidden" name="margin_percentage" value="0">
        <input type="hidden" id="tax_rate" name="tax_rate" value="21">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Company Information --}}
            <div
                class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Information</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Your registered business details</p>
                    </div>
                </div>
                <div class="p-6">
                    <div
                        class="mb-4 flex items-center gap-3 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-50/50 px-4 py-3 dark:from-emerald-900/20 dark:to-emerald-900/10">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm dark:bg-neutral-900">
                            <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-900 dark:text-emerald-300">
                                {{ $meta['wholesale_company_name'] ?? ($meta['company_name'] ?? '—') }}</p>
                            <p class="text-xs text-emerald-700/70 dark:text-emerald-400/60">Registered Business</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div
                            class="flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-neutral-500">Address</p>
                                <p class="text-sm text-slate-700 dark:text-neutral-300 truncate">
                                    {{ $meta['address'] ?? '—' }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-neutral-500">Email</p>
                                <p class="text-sm text-slate-700 dark:text-neutral-300 truncate">
                                    {{ $meta['email'] ?? ($user->email ?? '—') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-neutral-500">Phone</p>
                                <p class="text-sm text-slate-700 dark:text-neutral-300 truncate">
                                    {{ $meta['phone'] ?? ($user->phone ?? '—') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-400 dark:text-neutral-500">VAT Number</p>
                                <p class="text-sm text-slate-700 dark:text-neutral-300 truncate">
                                    {{ $meta['vat_number'] ?? ($meta['vat'] ?? '—') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer --}}
            <div
                class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Customer</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Select the reseller customer</p>
                    </div>
                </div>
                <div class="p-6">
                    @if ($resellers->isEmpty())
                        <div
                            class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-8 dark:border-neutral-700">
                            <svg class="mb-3 h-10 w-10 text-slate-300 dark:text-neutral-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <p class="text-sm font-medium text-slate-500 dark:text-neutral-400">No customers found</p>
                            <p class="text-xs text-slate-400 dark:text-neutral-500 mt-1">Add retailer users first to
                                create quotations</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <livewire:customer-select :selectedId="old('reseller_id')" :users="$resellers" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Quotation Info --}}
            <div
                class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Quotation Info</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Number, dates, and reference</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-forms.input name="quotation_number" label="Quotation Number" :value="$quotationNumber" readonly />
                        <x-forms.input name="reference" label="Reference" placeholder="e.g. PO-12345" />
                        <x-forms.input name="issue_date" id="issue_date" label="Issue Date" type="date"
                            :value="now()->format('Y-m-d')" min="{{ now()->format('Y-m-d') }}" />
                        <x-forms.input name="valid_until" id="valid_until" label="Valid Until" type="date"
                            min="{{ now()->format('Y-m-d') }}" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Margin (%)</label>
                            <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-neutral-300">
                                <span id="margin_percentage">0.00%</span>
                                <svg class="ml-auto h-4 w-4 shrink-0 text-slate-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Delivery</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Delivery country for VAT calculation
                        </p>
                    </div>
                </div>
                <div class="p-6">
                    <x-forms.select name="delivery_country" id="delivery_country" label="Delivery Country"
                        :options="[
                            'NL' => 'Netherlands',
                            'BE' => 'Belgium',
                            'DE' => 'Germany',
                            'FR' => 'France',
                            'IT' => 'Italy',
                            'ES' => 'Spain',
                            'GB' => 'United Kingdom',
                            'OTHER' => 'Other (Outside EU)',
                        ]" value="NL" />
                </div>
            </div>
        </div>
        {{-- Items --}}
        <livewire:items-manager :customerId="old('reseller_id')" :productId="$productId ?? null" :productIds="$cartIds ?? []" />

        {{-- Notes & Terms --}}
        <div
            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-teal-500 to-teal-600 shadow-sm">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Notes &amp; Terms</h2>
                    <p class="text-xs text-slate-500 dark:text-neutral-400">Add notes, terms, or special instructions
                    </p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" name="notes" id="notes_input" value="{{ old('notes') }}">
                <div id="notes_editor"
                    class="min-h-[200px] rounded-xl border border-slate-200 bg-white text-sm text-slate-900 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-end gap-3">
            <a href="{{ route('client.quotations.index') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                Cancel
            </a>
            <button type="submit" data-action="draft"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                Save Draft
            </button>
            <!-- <button type="submit" data-action="preview"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                preview
            </button> -->
            <button type="submit" data-action="pdf"
                class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-rose-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:bg-rose-600 dark:hover:bg-rose-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                </svg>
                PDF
            </button>
            <button type="submit" data-action="send"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
                Send
            </button>
        </div>
    </form>



    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script src="{{ asset('assets/js/Quantions.js') }}"></script>
    @endpush
</x-layouts.app>
