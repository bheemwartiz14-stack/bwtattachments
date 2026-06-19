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
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950">Forgot your password?</h2>
                    <p class="mt-2 text-sm text-slate-600">No problem. Just let us know your email address and we will email you a password reset link.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus class="mt-1.5 block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm text-black placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Send Password Reset Link
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600">
                    <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Back to sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
