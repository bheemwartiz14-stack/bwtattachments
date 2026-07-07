@php
    $breadcrumbLabel = match ($prefix) {
        'admin' => 'Admin',
        'client' => 'Client Portal',
        'retailer' => 'Retailer Portal',
        'customer' => 'customer Portal',
        default => 'Dashboard',
    };
    $activeTab = request()->query('tab', 'personal');
@endphp
<x-layouts.app>
    <x-slot:title>Profile Settings - {{ $siteTitle }}</x-slot:title>
    <x-breadcrumb :items="[['label' => $breadcrumbLabel, 'url' => route($prefix . '.dashboard')], ['label' => 'Profile Settings']]" />

    @if (session('success'))
        <div
            class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-3.5 dark:border-red-900/50 dark:bg-red-900/30">
            @if (session('error'))
                <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
            @endif
            @if ($errors->any())
                <ul class="list-disc pl-5 text-sm text-red-800 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Settings Sidebar --}}
            <div class="lg:w-64 shrink-0">
                <div class="lg:sticky lg:top-28 space-y-1">
                    <a href="{{ route($prefix . '.profile.edit', ['tab' => 'personal']) }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'personal' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Personal Information
                    </a>
                    @if ($user->hasRole('Wholesale Client') || $user->hasRole('Retailer') || $user->hasRole('customer'))
                        <a href="{{ route($prefix . '.profile.edit', ['tab' => 'company']) }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'company' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                            Company Details
                        </a>
                    @endif
                    <a href="{{ route($prefix . '.profile.edit', ['tab' => 'security']) }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ $activeTab === 'security' ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        Security
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0 space-y-8">
                @if ($activeTab === 'personal')
                    {{-- Personal Information Card --}}
                    <div>
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Personal
                                Information</h1>
                            <p class="text-sm text-slate-500 mt-1">Update your name, phone number, and profile photo</p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                            <form method="POST" action="{{ route($prefix . '.profile.update') }}" class="p-8">
                                @csrf
                                @method('PUT')
                                <div class="space-y-8">
                                    {{-- Avatar --}}
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                        <div class="shrink-0">
                                            <div class="relative">
                                                <div
                                                    class="h-24 w-24 overflow-hidden rounded-full ring-4 ring-slate-100 dark:ring-neutral-800">
                                                    @if ($avatarUrl)
                                                        <img src="{{ $avatarUrl }}" alt="Avatar"
                                                            class="h-full w-full object-cover">
                                                    @else
                                                        <div
                                                            class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600 text-3xl font-bold text-white">
                                                            {!! Avatar::create($user->name)->setDimension(32)->setFontSize(14)->toSvg() !!}
                                                        </div>
                                                    @endif
                                                </div>
                                                <label for="avatar_input"
                                                    class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-white shadow-md ring-2 ring-white transition-all hover:bg-slate-50 hover:scale-110 dark:bg-neutral-800 dark:ring-neutral-900 dark:hover:bg-neutral-700">
                                                    <svg class="h-4 w-4 text-slate-600 dark:text-neutral-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </label>
                                                <input type="file" id="avatar_input"
                                                    accept="image/jpeg,image/png,image/webp" class="hidden">
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-slate-900 dark:text-white">
                                                {{ $user->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-neutral-400 mt-0.5">
                                                {{ $user->email }}</p>
                                        </div>
                                        @if ($avatarUrl)
                                            <button type="button" id="remove-avatar-btn"
                                                class="shrink-0 rounded-lg border border-red-200 bg-white px-4 py-2 text-xs font-medium text-red-600 transition-colors hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                                                Remove
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Fields --}}
                                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                                        <div>
                                            <label for="name"
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Full
                                                Name</label>
                                            <div class="relative">
                                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                </svg>
                                                <input type="text" name="name" id="name"
                                                    value="{{ old('name', $user->name) }}" required
                                                    class="block w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm text-black placeholder-slate-400 transition-all focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                            </div>
                                            @error('name')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Email</label>
                                            <div
                                                class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">
                                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                        </div>

                                        <div>
                                            <label for="phone"
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Phone</label>
                                            <div class="relative">
                                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                                </svg>
                                                <input type="text" name="phone" id="phone"
                                                    value="{{ old('phone', $user->phone) }}"
                                                    class="block w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm text-black placeholder-slate-400 transition-all focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                            </div>
                                            @error('phone')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="avatar_temp" id="avatar_temp" value="">
                                    <div
                                        class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-neutral-800">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($activeTab === 'company')
                    {{-- Company Details --}}
                    <div>
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Company
                                Details</h1>
                            <p class="text-sm text-slate-500 mt-1">Your registered business information</p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                            <div class="p-8">
                                <div class="flex flex-col sm:flex-row items-start gap-6">
                                    @if ($logo ?? null)
                                        <div class="shrink-0">
                                            <img src="{{ $logo }}" alt="Company Logo"
                                                class="h-24 w-24 rounded-xl border border-slate-200 object-contain p-3 dark:border-neutral-700">
                                        </div>
                                    @else
                                        <div
                                            class="shrink-0 flex h-24 w-24 items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900">
                                            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1 space-y-3">
                                        <div>
                                            <p
                                                class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-neutral-500">
                                                Company Name</p>
                                            <p class="text-base font-semibold text-slate-900 dark:text-white mt-0.5">
                                                {{ $company_name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                                {{ \Illuminate\Support\Str::ucfirst($prefix) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($activeTab === 'security')
                    {{-- Change Password --}}
                    <div>
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Security
                            </h1>
                            <p class="text-sm text-slate-500 mt-1">Update your password to keep your account secure</p>
                        </div>
                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                            <form method="POST" action="{{ route($prefix . '.profile.password') }}" class="p-8">
                                @csrf
                                @method('PUT')
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 lg:grid-cols-2">
                                        <div>
                                            <label for="current_password"
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Current
                                                Password</label>
                                            <div class="relative">
                                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                                <input type="password" name="current_password" id="current_password"
                                                    required
                                                    class="block w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm text-black placeholder-slate-400 transition-all focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                            </div>
                                            @error('current_password')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password"
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">New
                                                Password</label>
                                            <div class="relative">
                                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                <input type="password" name="password" id="password" required
                                                    class="block w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm text-black placeholder-slate-400 transition-all focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                            </div>
                                            @error('password')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation"
                                                class="block text-sm font-medium text-slate-700 dark:text-neutral-300 mb-1.5">Confirm
                                                Password</label>
                                            <div class="relative">
                                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                                </svg>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" required
                                                    class="block w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2.5 text-sm text-black placeholder-slate-400 transition-all focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-neutral-800">
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-950">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var csrfToken = '{{ csrf_token() }}';
                var uploadUrl = '{{ route('upload-temp') }}';
                var tempInput = document.getElementById('avatar_temp');
                var fileInput = document.getElementById('avatar_input');
                var avatarContainer = document.querySelector('.h-24.w-24');

                if (fileInput) {
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
                                    avatarContainer.innerHTML = '<img src="' + data.url +
                                        '" alt="Avatar" class="h-full w-full object-cover">';
                                }
                            }
                        };
                        xhr.send(formData);
                    });
                }

                var removeBtn = document.getElementById('remove-avatar-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route($prefix . '.profile.avatar.delete') }}');
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
    @endpush
</x-layouts.app>
