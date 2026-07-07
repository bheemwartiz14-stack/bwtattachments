<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="mb-8 text-center">
                    <div class="mb-4 flex justify-center">
                        <span class="grid h-12 w-12 place-items-center overflow-hidden rounded-xl bg-emerald-600">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Reset Password</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-neutral-400">Enter your new password below.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
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
                        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Email address</label>
                        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" readonly class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-400">

                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">New Password</label>
                        <div class="relative mt-1.5">
                            <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Enter new password" class="block w-full rounded-xl border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-black placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500">
                            <button type="button" onclick="const i=this.previousElementSibling;const e=this.querySelectorAll('svg');i.type=i.type==='password'?'text':'password';e[0].classList.toggle('hidden');e[1].classList.toggle('hidden')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 0 1 3.602-4.94M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path d="M10.73 5.08A10.07 10.07 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.07 10.07 0 0 1-2.482 3.482" />
                                    <path d="M1 1l22 22" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Confirm Password</label>
                        <div class="relative mt-1.5">
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password" class="block w-full rounded-xl border-slate-200 bg-white px-4 py-3 pr-11 text-sm text-black placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500">
                            <button type="button" onclick="const i=this.previousElementSibling;const e=this.querySelectorAll('svg');i.type=i.type==='password'?'text':'password';e[0].classList.toggle('hidden');e[1].classList.toggle('hidden')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 0 1 3.602-4.94M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                    <path d="M10.73 5.08A10.07 10.07 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.07 10.07 0 0 1-2.482 3.482" />
                                    <path d="M1 1l22 22" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500 dark:focus:ring-offset-neutral-900">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
