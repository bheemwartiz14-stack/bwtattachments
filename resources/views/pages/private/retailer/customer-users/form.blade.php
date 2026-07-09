@php
    $isEdit = isset($user);
    $meta = $meta ?? ($user->userMeta?->metadata ?? []);
    $logoUrl = $isEdit ? $user->getFirstMediaUrl('customer_logo') : null;
    $commission = $isEdit ? ($user->userMargin?->margin_value ?? '') : '';
@endphp

<x-layouts.app>
    <x-slot:title>{{ $isEdit ? 'Edit' : 'Add' }} Customer User - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('client.dashboard')], ['label' => 'Customer Users', 'url' => route('client.retailer-users.index')], ['label' => $isEdit ? 'Edit' : 'New']]" />
    <div class="space-y-4">
        <x-ui.hero title="{{ $isEdit ? 'Edit' : 'Add' }} Customer User" subtitle="{{ $isEdit ? 'Update Customer user details and settings' : 'Create a new Customer user account' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            </x-slot:icon>
        </x-ui.hero>
        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-900/50 dark:bg-red-900/20">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Please fix {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
                        <ul class="mt-1.5 list-disc space-y-1 text-sm text-red-700 dark:text-red-400 [&_li]:ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ $isEdit ? route('retailer.customer-users.update', $user) : route('retailer.customer-users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if($isEdit) @method('PUT') @endif

             <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-5 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/30">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21h10.5" /></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Details</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Business information and registration</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                        <x-forms.input
                            name="company_name"
                            label="Company Name"
                            placeholder="Acme Corp Ltd"
                            :value="$meta['company_name'] ?? ''"
                            :required="true"
                            :error="$errors->first('company_name')"
                        />
                        <x-forms.input
                            name="vat_number"
                            label="VAT Number"
                            placeholder="GB123456789"
                            :value="$meta['vat_number'] ?? ''"
                            :required="true"
                            :error="$errors->first('vat_number')"
                        />
                        <x-forms.input
                            name="address"
                            label="Address"
                            placeholder="123 Business Street"
                            :value="$meta['address'] ?? ''"
                            :required="true"
                            :error="$errors->first('address')"
                        />
                        <x-forms.input
                            name="postal_code"
                            label="Postal Code"
                            placeholder="SW1A 1AA"
                            :value="$meta['postal_code'] ?? ''"
                            :required="true"
                            :error="$errors->first('postal_code')"
                        />
                        <x-forms.input
                            name="city"
                            label="City"
                            placeholder="London"
                            :value="$meta['city'] ?? ''"
                            :required="true"
                            :error="$errors->first('city')"
                        />
                        <x-forms.input
                            name="country"
                            label="Country"
                            placeholder="United Kingdom"
                            :value="$meta['country'] ?? ''"
                            :required="true"
                            :error="$errors->first('country')"
                        />
                        <x-forms.input
                            name="website"
                            label="Website"
                            type="url"
                            placeholder="https://acme.com"
                            :value="$meta['website'] ?? ''"
                            :required="false"
                            :hint="'Optional'"
                            :error="$errors->first('website')"
                        />
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-800">
                        <svg class="h-5 w-5 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Account Details</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">

                        <x-forms.input
                            name="name"
                            label="Full Name"
                            placeholder="John Doe"
                            :value="$isEdit ? $user->name : ''"
                            :required="true"
                            :generateUsername="$isEdit ? '' : '#username'"
                            :error="$errors->first('name')"
                        />
                        <x-forms.email
                            name="email"
                            label="Email Address"
                            placeholder="retailer@company.com"
                            :value="$isEdit ? $user->email : ''"
                            :required="!$isEdit"
                            :disabled="$isEdit"
                            :error="$errors->first('email')"
                            :hint="$isEdit ? 'Email cannot be changed after creation' : ''"
                        />
                        <x-forms.phone
                            name="phone"
                            label="Phone Number"
                            placeholder="Enter phone number"
                            :value="$isEdit ? $user->phone : ''"
                            :required="true"
                            :error="$errors->first('phone')"
                        />
                        <x-forms.input
                            name="username"
                            id="username"
                            label="Username"
                            placeholder="Auto-generated from name"
                            :value="$isEdit ? $user->username : ''"
                            :readonly="true"
                            :required="true"
                            :error="$errors->first('username')"
                            :hint="$isEdit ? 'Username cannot be changed' : ''"
                        />
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-3">
                                @if(!$isEdit)
                                <div>
                                    <x-forms.password
                                        name="password"
                                        label="Password"
                                        :required="true"
                                        :showGenerator="false"
                                        :error="$errors->first('password')"
                                    />
                                </div>
                                @endif
                                <div>
                                    <input type="hidden" name="parent_id" value="{{ auth()->id() }}">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Wholesale Client</label>
                                    <div class="inline-flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm dark:border-neutral-700 dark:bg-neutral-800">
                                        <svg class="h-5 w-5 shrink-0 text-slate-500 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span class="font-semibold text-slate-800 dark:text-neutral-200">{{ auth()->user()->name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="roles" value="{{ $userRole ?? 'customer' }}">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Role</label>
                                    <div class="inline-flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm dark:border-emerald-800 dark:bg-emerald-900/20">
                                        <svg class="h-5 w-5 text-emerald-600 shrink-0 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                        <span class="font-semibold text-emerald-800 dark:text-emerald-300">{{ $userRole ?? 'Customer' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-800">
                        <svg class="h-5 w-5 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Customer Profile</h2>
                </div>
                <div class="p-8">
                    <x-forms.image-dropzone name="customer_logo"    :existingImageUrl="$logoUrl"
                             label="Customer Logo"
                            accept="image/jpeg,image/png,image/webp"
                            hint="PNG, JPG or WebP (Max. 2MB)" />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Customer Commission Settings</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                        <x-forms.input
                            name="commission_percentage"
                            label="Commission Percentage (%)"
                            type="number"
                            placeholder="e.g. 10"
                            :value="$commission"
                            min="0"
                            max="100"
                            step="0.01"
                            :error="$errors->first('commission_percentage')"
                            hint="Default commission percentage applied to product pricing for this retailer."
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 rounded-2xl border border-slate-200 bg-white px-8 py-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <a href="{{ route('client.retailer-users.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-slate-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6m-3-3h6"/></svg>
                    {{ $isEdit ? 'Update User' : 'Create User' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            $('#name').on('input', function () {
                const name = $(this).val();
                const username = name.toLowerCase().replace(/[^a-z0-9\s]/g, '').trim().replace(/\s+/g, '-');
                $('#username').val(username);
            });
            (function() {
                var pwd = document.querySelector('[data-password-input]');
                if (pwd && !pwd.value) {
                    var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$';
                    var s = '';
                    for (var i = 0; i < 10; i++) s += chars[Math.floor(Math.random() * chars.length)];
                    pwd.value = s;
                }
            })();
        </script>
    @endpush
</x-layouts.app>
