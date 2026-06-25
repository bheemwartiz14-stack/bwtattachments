<x-guest-layout>
    <div class="mx-auto flex min-h-screen items-center px-4 sm:px-6 lg:max-w-6xl">
        <div class="grid w-full overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl dark:border-neutral-800 dark:bg-neutral-950 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="hidden bg-slate-900 p-10 text-white lg:flex lg:flex-col lg:justify-between dark:bg-black">
                <div>
                    <div class="inline-flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
                        <span class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-emerald-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide">Attachment</p>
                            <p class="text-xs text-slate-300 dark:text-neutral-400">Operations Portal</p>
                        </div>
                    </div>

                    <div class="mt-16 max-w-md">
                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-300">B2B Wholesale</p>
                        <h1 class="mt-4 text-4xl font-bold tracking-tight">Manage your attachment inventory from one focused dashboard.</h1>
                        <p class="mt-5 text-base leading-7 text-slate-300 dark:text-neutral-400">Track products, quotations, clients, and daily sales performance after sign in.</p>
                    </div>
                </div>
            </section>
            <section class="flex items-center justify-center bg-slate-50 px-5 py-10 sm:px-8 lg:px-12 dark:bg-neutral-900">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden">
                        <div class="inline-flex items-center gap-3">
                            <span class="grid h-11 w-11 place-items-center overflow-hidden rounded-xl bg-emerald-600 shadow-sm ring-1 ring-slate-200 dark:ring-neutral-700">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-slate-950 dark:text-neutral-100">Attachment</p>
                                <p class="text-xs text-slate-500 dark:text-neutral-400">Operations Portal</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">Welcome back</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Login to your account</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-neutral-400">Enter your details to continue to the dashboard.</p>
                    </div>
                    @if (session('status'))
                        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                        @csrf
                        <div>
                            <label for="indenify" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Username Or Email</label>
                            <div class="relative mt-2">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11Z" />
                                    <path d="m5 7 7 6 7-6" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input id="indenify" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-4 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500 dark:focus:border-slate-400 dark:focus:ring-slate-400" type="text" name="indenify" value="{{ old('indenify') }}" required autofocus autocomplete="username" placeholder="Email or username">
                            </div>
                            @error('indenify')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="flex items-center justify-between gap-3">
                                <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Password</label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300" href="{{ route('password.request') }}">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            <div class="relative mt-2">
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke-linecap="round" />
                                    <path d="M6.5 10h11A2.5 2.5 0 0 1 20 12.5v5A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-5A2.5 2.5 0 0 1 6.5 10Z" />
                                </svg>
                                <input id="password" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-11 text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500 dark:focus:border-slate-400 dark:focus:ring-slate-400" type="password" name="password" required autocomplete="current-password" placeholder="Enter password">
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

                        {{-- Remember Me --}}
                        <label for="remember_me" class="flex items-center gap-2">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-slate-500 dark:border-neutral-600 dark:bg-neutral-950 dark:text-emerald-500 dark:focus:ring-slate-400" name="remember">
                            <span class="text-sm text-slate-600 dark:text-neutral-400">Remember me on this device</span>
                        </label>

                        {{-- Submit --}}
                        <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500 dark:focus:ring-offset-neutral-900">
                            Login
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
