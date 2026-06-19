<x-guest-layout>
    <div class="mx-auto flex min-h-screen items-center px-4 sm:px-6 lg:max-w-6xl">
        <div class="grid w-full overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl lg:grid-cols-[1.05fr_0.95fr]">
            <section class="hidden bg-slate-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                <div>
                    <div class="inline-flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
                        <span class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-emerald-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide">Attachment</p>
                            <p class="text-xs text-slate-300">Operations Portal</p>
                        </div>
                    </div>
                    <div class="mt-16 max-w-md">
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">First time login</p>
                        <h1 class="mt-4 text-4xl font-bold tracking-tight">Set your account password.</h1>
                        <p class="mt-5 text-base leading-7 text-slate-300">Choose a new password to activate your account and access the dashboard.</p>
                    </div>
                </div>
            </section>
            <section class="flex items-center justify-center bg-slate-50 px-5 py-10 sm:px-8 lg:px-12">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden">
                        <div class="inline-flex items-center gap-3">
                            <span class="grid h-11 w-11 place-items-center overflow-hidden rounded-xl bg-emerald-600 shadow-sm ring-1 ring-slate-200">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-slate-950">Attachment</p>
                                <p class="text-xs text-slate-500">Operations Portal</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-emerald-700">Set new password</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">Welcome!</h2>
                        <p class="mt-2 text-sm text-slate-500">This is your first time logging in. Please set a new password to continue.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('first-time-password.update') }}" class="mt-8 space-y-5">
                        @csrf

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700">New Password</label>
                            <div class="relative mt-2">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke-linecap="round" />
                                    <path d="M6.5 10h11A2.5 2.5 0 0 1 20 12.5v5A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-5A2.5 2.5 0 0 1 6.5 10Z" />
                                </svg>
                                <input id="password" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password">
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                            <div class="relative mt-2">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke-linecap="round" />
                                    <path d="M6.5 10h11A2.5 2.5 0 0 1 20 12.5v5A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-5A2.5 2.5 0 0 1 6.5 10Z" />
                                </svg>
                                <input id="password_confirmation" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-4 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
                            </div>
                        </div>

                        <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Save & Continue
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>
