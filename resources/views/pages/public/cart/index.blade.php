<x-layouts.public>
    <x-slot:title>Shopping Cart - BWT</x-slot:title>

    <div class="w-full px-2 sm:px-4 lg:px-6 py-6">
        <div class="mx-auto max-w-7xl">
            @if (session('success'))
                <div
                    class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800/50 dark:bg-emerald-900/20 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            @if ($cartItems->isNotEmpty())
                <div class="mt-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Your Cart</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Review items before requesting
                                your quotation.</p>
                        </div>
                        <span
                            class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span data-cart-header-count>{{ $cartItems->count() }}
                                {{ $cartItems->count() === 1 ? 'item' : 'items' }}</span>
                        </span>
                    </div>
                    <livewire:cart-items-manager :productIds="$cartIds" />
                    <div
                        class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-white px-4 py-4 sm:flex-row sm:justify-end dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('public.home.index') }}" wire:navigate
                                class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Continue Shopping
                            </a>
                            @auth
                                @php
                                    $quoteRoute = match (true) {
                                        auth()->user()->hasRole('Wholesaler') => route('client.orders.create'),
                                        auth()->user()->hasRole('Reseller') => route('reseller.orders.create'),
                                        auth()->user()->hasRole('customer') => null,
                                        auth()->user()->hasRole('Admin') => null,
                                        default => null,
                                    };
                                @endphp
                                @if ($quoteRoute)
                                    <a href="{{ $quoteRoute }}" wire:navigate
                                        class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-900/10 hover:bg-black dark:bg-emerald-600 dark:hover:bg-emerald-700">
                                        Next step
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="mt-8 mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">Your cart is empty</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-neutral-400">Browse products and add attachments to
                        build your quotation.</p>
                    <a href="{{ route('public.home.index') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
                        Browse products
                    </a>
                </div>
            @endif
        </div>
    </div>

@push('scripts')
    <script src="{{ asset('assets/js/Order.js') }}"></script>
@endpush
</x-layouts.public>
