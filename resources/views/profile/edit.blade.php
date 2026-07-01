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
            <p class="text-sm text-gray-700 mt-1">Manage your personal information and account settings</p>
        </div>

        {{-- Avatar Card --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-5 dark:border-neutral-800">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/30">
                    <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Personal Information</h2>
                    <p class="text-xs text-slate-500 dark:text-neutral-400">Update your name, phone number, and profile photo</p>
                </div>
            </div>
            <form method="POST" action="{{ route($prefix . '.profile.update') }}" class="p-8">
                @csrf
                @method('PUT')
                <div class="space-y-8">
                    {{-- Avatar Upload --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="shrink-0">
                            <div class="relative">
                                <div class="h-24 w-24 overflow-hidden rounded-full ring-4 ring-slate-100 dark:ring-neutral-800">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="Avatar" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600 text-3xl font-bold text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <label for="avatar_input" class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-white shadow-md ring-2 ring-white transition-colors hover:bg-slate-50 dark:bg-neutral-800 dark:ring-neutral-900 dark:hover:bg-neutral-700">
                                    <svg class="h-4 w-4 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </label>
                                <input type="file" id="avatar_input" accept="image/jpeg,image/png,image/webp" class="hidden">
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">{{ $user->email }}</p>
                        </div>
                        @if($avatarUrl)
                            <button type="button" id="remove-avatar-btn" class="shrink-0 rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-medium text-red-600 transition-colors hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                                Remove Photo
                            </button>
                        @endif
                    </div>

                    {{-- Personal Info Fields --}}
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
                            <label class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Email Address</label>
                            <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                <span>{{ $user->email }}</span>
                            </div>
                            <input type="hidden" name="email" value="{{ $user->email }}">
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

                    {{-- Hidden avatar temp input --}}
                    <input type="hidden" name="avatar_temp" id="avatar_temp" value="">

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-neutral-800">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Company Details (Read-only) --}}
        @if($user->hasRole('Wholesale Client') || $user->hasRole('Retailer'))
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center gap-3 border-b border-slate-100 px-8 py-5 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/30">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Company Details</h2>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Your registered business information (managed by admin)</p>
                    </div>
                </div>
                <div class="p-8">
                    @if($user->hasRole('Wholesale Client'))
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            @if($wholesaleClientLogoUrl ?? null)
                                <div class="shrink-0">
                                    <img src="{{ $wholesaleClientLogoUrl }}" alt="Company Logo" class="h-20 w-20 rounded-xl border border-slate-200 object-contain p-2 dark:border-neutral-700">
                                </div>
                            @endif
                            <div class="min-w-0 flex-1 space-y-3">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Company Name</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white mt-0.5">{{ $wholesaleClientName ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Account Type</p>
                                    <p class="mt-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            Wholesale Client
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($user->hasRole('Retailer'))
                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            @if($retailerClientLogoUrl ?? null)
                                <div class="shrink-0">
                                    <img src="{{ $retailerClientLogoUrl }}" alt="Company Logo" class="h-20 w-20 rounded-xl border border-slate-200 object-contain p-2 dark:border-neutral-700">
                                </div>
                            @endif
                            <div class="min-w-0 flex-1 space-y-3">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Company Name</p>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white mt-0.5">{{ $retailerClientName ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">Account Type</p>
                                    <p class="mt-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            Retailer
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

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
    <script>
    (function() {
        var csrfToken = '{{ csrf_token() }}';
        var uploadUrl = '{{ route("upload-temp") }}';
        var tempInput = document.getElementById('avatar_temp');
        var fileInput = document.getElementById('avatar_input');
        var avatarContainer = document.querySelector('.h-24.w-24');

        fileInput.addEventListener('change', function() {
            var file = this.files[0];
            if (!file) return;

            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    tempInput.value = JSON.stringify(data);
                    var img = avatarContainer.querySelector('img');
                    if (img) {
                        img.src = data.url;
                    } else {
                        avatarContainer.innerHTML = '<img src="' + data.url + '" alt="Avatar" class="h-full w-full object-cover">';
                    }
                }
            };
            xhr.send(formData);
        });

        var removeBtn = document.getElementById('remove-avatar-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route($prefix . ".profile.avatar.delete") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('X-HTTP-Method-Override', 'DELETE');
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        window.location.reload();
                    }
                };
                xhr.send();
            });
        }
    })();
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-layouts.app>
