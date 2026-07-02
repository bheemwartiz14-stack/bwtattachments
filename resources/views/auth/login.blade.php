<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                @php $settings = app(\App\Settings\GeneralSettings::class); @endphp

                <div class="mb-8 text-center">
                    <div class="mb-4 flex justify-center">
                        <img src="{{ $settings->logo_path ? asset($settings->logo_path) : asset('images/bwt-logo.jpg') }}"
                             alt="BWT" class="h-10 w-auto">
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Sign in</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-neutral-400">Enter your credentials to continue.</p>
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

                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5" novalidate>
                    @csrf

                    <div>
                        <label for="login" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Email or username</label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="name@company.com"
                            class="mt-1.5 block w-full rounded-xl border bg-white px-4 py-3 text-sm text-black placeholder-slate-400 shadow-sm outline-none transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 @error('login') border-red-400 ring-2 ring-red-200 @enderror dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500">
                        <p class="mt-1.5 hidden text-sm font-medium text-red-600" id="loginError"></p>
                        @error('login')
                            <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-neutral-300">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300" href="{{ route('password.request') }}">Forgot?</a>
                            @endif
                        </div>
                        <div class="relative mt-1.5">
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"
                                class="block w-full rounded-xl border bg-white px-4 py-3 pr-10 text-sm text-black placeholder-slate-400 shadow-sm outline-none transition-colors focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 @error('password') border-red-400 ring-2 ring-red-200 @enderror dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder-neutral-500">
                            <button type="button" onclick="const p=this.previousElementSibling;p.type=p.type==='password'?'text':'password'"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:text-neutral-500 dark:hover:text-neutral-300">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1.5 hidden text-sm font-medium text-red-600" id="passwordError"></p>
                        @error('password')
                            <p class="mt-1.5 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:border-neutral-600 dark:bg-neutral-950 dark:text-emerald-500">
                        <label for="remember_me" class="text-sm text-slate-600 dark:text-neutral-400">Remember me</label>
                    </div>

                    <button type="submit"
                        class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500 dark:focus:ring-offset-neutral-900">
                        Sign in
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600 dark:text-neutral-400">
                    Having trouble?
                    <a href="{{ route('password.request') }}" class="font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">Reset your password</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const login = document.getElementById('login');
            const password = document.getElementById('password');
            const loginError = document.getElementById('loginError');
            const passwordError = document.getElementById('passwordError');
            let valid = true;

            loginError.classList.add('hidden');
            passwordError.classList.add('hidden');
            login.classList.remove('border-red-400', 'ring-2', 'ring-red-200');
            password.classList.remove('border-red-400', 'ring-2', 'ring-red-200');

            if (!login.value.trim()) {
                loginError.textContent = 'Please enter your email or username.';
                loginError.classList.remove('hidden');
                login.classList.add('border-red-400', 'ring-2', 'ring-red-200');
                valid = false;
            }

            if (!password.value.trim()) {
                passwordError.textContent = 'Please enter your password.';
                passwordError.classList.remove('hidden');
                password.classList.add('border-red-400', 'ring-2', 'ring-red-200');
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</x-guest-layout>
