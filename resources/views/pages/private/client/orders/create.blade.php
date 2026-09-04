<x-layouts.app>
    @php
        $logoMedia =
        $user->getFirstMedia('wholesale_client_logo') ?: $user->userMeta?->getFirstMedia('wholesale_client_logo');
        $logoUrl = $logoMedia?->getUrl();
        $logoId = $logoMedia?->id;
    @endphp
    @push('styles')
        <link href="{{ asset('assets/css/Quantions.css') }}" rel="stylesheet">
    @endpush
    <x-slot:title>New Order - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Wholesaler Portal', 'url' => route('client.dashboard')],
        ['label' => 'Orders', 'url' => route('client.orders.index')],
        ['label' => 'Complete your order'],
    ]" />

    <div class="mb-6">
        <x-ui.hero title="Complete your order" icon="heroicon-o-clipboard-document-list">
            <x-slot:actions>
                <span id="last-saved" class="text-xs text-gray-400"></span>
            </x-slot:actions>
        </x-ui.hero>
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

    <form id="order-form" action="{{ route('client.orders.store') }}" method="POST" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        <input type="hidden" id="form-action" name="action" value="draft">
        <input type="hidden" id="user_id" name="order_from_user_id" value="{{ $user->id }}">
        <input type="hidden" id="order_to_user_id" name="order_to_user_id" value="{{ $admin->id }}">
        <input type="hidden" id="items-json" name="items"
            value="{{ old('items', json_encode($cartItemsJson ?? $cartIds)) }}">
        <input type="hidden" id="margin_percentage_hidden" name="margin_percentage" value="{{ $usermargin }}">
        <input type="hidden" id="delivery_country" name="delivery_country" value="{{ old('delivery_country', $vatList['iso_code'] ?? '') }}">
        <x-forms.input name="order_number" :value="$orderNumber" readonly hidden />
        <x-forms.input name="order_date" type="date" :value="now()->format('Y-m-d')" hidden />
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Quotation Info --}}
            <div
                class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                {{-- Content --}}
                <div class="p-6">
                    <div class="max-w-xl space-y-6">

                        {{-- Company Logo --}}
                        <div>
                            <x-forms.image-dropzone name="wholesale_client_logo" :existingImageUrl="$logoUrl" :existingImageId="$logoId"
                                label="Company Logo" accept="image/jpeg,image/png,image/webp"
                                hint="PNG, JPG or WebP (Max. 2MB)" />
                        </div>

                        {{-- PDF Logo Toggle --}}
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 dark:border-neutral-700 dark:bg-neutral-900">
                            <x-forms.toggle name="show_logo_on_pdf" label="Show Logo on PDF"
                                description="Display the company logo on generated PDF documents." />
                        </div>

                    </div>
                </div>
            </div>
            {{-- Send To --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 003.712.872M15 19.128v-3.13m0 3.13a9.38 9.38 0 01-3.712.872M15 15.998a9.38 9.38 0 00-3.712-.872M3 7.5h18M12 3v1.5m0 15V21m-6.364-3.636l1.06-1.06M17.304 7.696l1.06-1.06M4.5 12H3m18 0h-1.5M6.696 7.696l-1.06-1.06m12.728 10.728l-1.06-1.06M12 18a6 6 0 100-12 6 6 0 000 12z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Send To</h2>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-neutral-400">Wholesaler notification recipient</p>
                    </div>
                </div>
                <div class="p-6 flex-1">
                    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/60">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700 ring-4 ring-white dark:bg-blue-900/50 dark:text-blue-400 dark:ring-neutral-950">
                            {{ strtoupper(substr($admin?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $admin?->name ?? 'Unknown User' }}</p>
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Admin</span>
                            </div>
                            <p class="mt-1 truncate text-xs text-slate-500 dark:text-neutral-400">{{ $admin?->email ?? 'No email available' }}</p>
                            @if ($admin?->phone)
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-neutral-400">{{ $admin->phone }}</p>
                            @endif
                        </div>
                        <div class="hidden shrink-0 sm:block">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>
                    </div>
                </div>
            </section>
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
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Order Information</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Number, date, and reference</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="max-w-xl">
                        <x-forms.input name="order_reference" label="Order Reference" placeholder="e.g. PO-12345"
                            help="Your internal PO / reference for this order" />
                    </div>
                </div>
            </div>

        </div>
        {{-- Items --}}
        <div
            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-rose-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 11.625l2.25-2.25M12 11.625l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Items</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Add products to your Order</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <livewire:order-items-manager :productIds="$cartIds" :vatList="$vatList" />
            </div>
        </div>
        {{-- Quotation Email Message --}}
        <div
            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Order Email Message</h2>
                    <p class="text-xs text-slate-500 dark:text-neutral-400">Optional message included in the email sent
                        with this order</p>
                </div>
            </div>
            <div class="p-6">
                <x-forms.textarea name="order_email_message" label="Order Email Message"
                    placeholder="Enter the message that will be included in the email sent with this order."
                    :value="old('order_email_message')" rows="4" />
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
        <script src="{{ asset('assets/js/Order.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkbox = document.getElementById('need_logo_checkbox');
                const badge = document.getElementById('need_logo_badge');

                if (!checkbox || !badge) {
                    return;
                }

                checkbox.addEventListener('change', function() {
                    badge.textContent = this.checked ? 'Yes' : 'No';
                });
            });
        </script>
    @endpush
</x-layouts.app>
