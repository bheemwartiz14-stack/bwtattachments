<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.layouts.head')
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen flex-col">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ route('public.products.index') }}" class="inline-flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-emerald-600">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide text-slate-950">Attachment</p>
                            <p class="text-xs text-slate-500">Wholesale Catalog</p>
                        </div>
                    </a>
                    <nav class="flex items-center gap-6">
                        <a href="{{ route('public.products.index') }}" class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600">Products</a>
                        <a href="{{ route('public.products.index') }}" class="text-sm font-medium text-slate-700 transition-colors hover:text-emerald-600">Categories</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Login</a>
                        @endauth
                    </nav>
                </div>
            </header>

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 bg-slate-50">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-slate-500">&copy; {{ date('Y') }} Attachment Portal. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
