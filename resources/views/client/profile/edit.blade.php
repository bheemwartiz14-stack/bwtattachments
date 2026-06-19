<x-layouts.app>
    <x-slot:title>Profile Settings - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'Profile Settings']]" />

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

    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Profile Settings</h1>
            <p class="text-sm text-gray-700 mt-1">Manage your account information and password</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-6">Profile Information</h2>
            <form method="POST" action="{{ route('client.profile.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-6">Change Password</h2>
            <form method="POST" action="{{ route('client.profile.password') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
