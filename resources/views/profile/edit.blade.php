@php
    $breadcrumbLabel = match($prefix) {
        'admin' => 'Admin',
        'client' => 'Client Portal',
        'retailer' => 'Retailer Portal',
        default => 'Dashboard',
    };
@endphp

<x-layouts.app>
    <x-slot:title>Profile Settings - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[
        ['label' => $breadcrumbLabel, 'url' => route($prefix . '.dashboard')],
        ['label' => 'Profile Settings']
    ]" />

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 dark:border-red-900/50 dark:bg-red-900/30">
            <ul class="list-disc pl-5 text-sm text-red-800 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Profile Settings</h1>
            <p class="text-sm text-gray-700 mt-1">Manage your account and business profile</p>
        </div>

        {{-- Profile Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-5 dark:border-neutral-800">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Profile Information</h2>
                    <p class="text-xs text-slate-500 dark:text-neutral-400">Update your personal details and contact information</p>
                </div>
            </div>
            <form method="POST" action="{{ route($prefix . '.profile.update') }}" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($user->hasRole('Wholesale Client') || $user->hasRole('Retailer'))
                        <div class="border-t border-slate-100 dark:border-neutral-800"></div>

                        @if($user->hasRole('Wholesale Client'))
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Business Information</h3>
                                <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                                    <div>
                                        <label for="wholesale_client_name" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Wholesale Client Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="wholesale_client_name" id="wholesale_client_name" value="{{ old('wholesale_client_name', $wholesaleClientName ?? '') }}" required
                                            class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                        @error('wholesale_client_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-forms.image-dropzone
                                            name="wholesale_client_logo"
                                            :existingImageUrl="$wholesaleClientLogoUrl ?? null"
                                            :existingImageId="$wholesaleClientLogoId ?? null"
                                            label="Wholesale Client Logo"
                                            accept="image/jpeg,image/png,image/webp"
                                            hint="PNG, JPG or WebP (Max. 2MB)" />
                                        @error('wholesale_client_logo')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($user->hasRole('Retailer'))
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Business Information</h3>
                                <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                                    <div>
                                        <label for="retailer_client_name" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Retailer Client Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="retailer_client_name" id="retailer_client_name" value="{{ old('retailer_client_name', $retailerClientName ?? '') }}" required
                                            class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                        @error('retailer_client_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-forms.image-dropzone
                                            name="retailer_client_logo"
                                            :existingImageUrl="$retailerClientLogoUrl ?? null"
                                            :existingImageId="$retailerClientLogoId ?? null"
                                            label="Retailer Client Logo"
                                            accept="image/jpeg,image/png,image/webp"
                                            hint="PNG, JPG or WebP (Max. 2MB)" />
                                        @error('retailer_client_logo')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-neutral-800">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-5 dark:border-neutral-800">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/30">
                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Change Password</h2>
                    <p class="text-xs text-slate-500 dark:text-neutral-400">Update your password to keep your account secure</p>
                </div>
            </div>
            <form method="POST" action="{{ route($prefix . '.profile.password') }}" class="p-8">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">New Password</label>
                            <input type="password" name="password" id="password" required
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-neutral-800">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-layouts.app>
