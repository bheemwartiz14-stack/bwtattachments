<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm">
                <div class="mb-8 text-center">
                    <div class="mb-4 flex justify-center">
                        <span class="grid h-12 w-12 place-items-center overflow-hidden rounded-xl bg-emerald-600">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950">Reset Password</h2>
                    <p class="mt-2 text-sm text-slate-600">Enter your new password below.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" readonly class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 shadow-sm">

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700">New Password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Enter new password" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-black placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-black placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">

                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
