<x-layouts.app>
    <x-slot:title>Price Details - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Product Pricing', 'url' => route('admin.product-pricing.index')],
        ['label' => 'Price Details']
    ]" />

    <div class="space-y-6">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 shadow-2xl">
            <div class="relative flex flex-col md:flex-row">
                <div class="shrink-0 w-full md:w-72 md:h-auto bg-slate-800/50">
                    @if($price->product?->getFirstMediaUrl('images'))
                        <div class="aspect-[3/2] md:aspect-auto md:h-full">
                            <img src="{{ $price->product->getFirstMediaUrl('images', 'large') }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-white">{{ $price->product?->product_title ?? 'N/A' }}</h1>
                                <p class="mt-1.5 text-sm font-mono text-emerald-400">{{ $price->product?->product_code ?? '' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">{{ $price->type === 'wholesale_purchase' ? 'Wholesale Purchase Price' : ($price->type === 'wholesale_selling' ? 'Wholesale Selling Price' : 'Retail Price') }}</p>
                                <p class="text-3xl font-bold text-emerald-400">{{ config('app.currency_symbol') }}{{ number_format($price->price, 2) }}</p>
                                @if($price->margin)
                                    <p class="mt-1 text-xs text-slate-400">Margin: {{ number_format($price->margin, 1) }}%</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-6 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white/80 ring-1 ring-white/20">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                {{ $price->user?->name ?? 'N/A' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white/80 ring-1 ring-white/20">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ $price->user?->email ?? '' }}
                            </span>
                            @php
                                $typeBadge = [
                                    'wholesale_purchase' => 'bg-blue-500/20 text-blue-300 ring-blue-500/30',
                                    'wholesale_selling' => 'bg-purple-500/20 text-purple-300 ring-purple-500/30',
                                    'retail' => 'bg-amber-500/20 text-amber-300 ring-amber-500/30',
                                ][$price->type] ?? 'bg-white/10 text-white/80 ring-white/20';
                                $typeLabel = [
                                    'wholesale_purchase' => 'Wholesale Purchase',
                                    'wholesale_selling' => 'Wholesale Selling',
                                    'retail' => 'Retail',
                                ][$price->type] ?? $price->type;
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold ring-1 {{ $typeBadge }}">
                                {{ $typeLabel }}
                            </span>
                        </div>
                        @if($price->assignedBy)
                            <div class="mt-3 text-xs text-slate-400">
                                Assigned by {{ $price->assignedBy->name }}
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('admin.product-pricing.edit', $price) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-400 hover:shadow-emerald-400/30 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Price
                        </a>
                        <a href="{{ route('admin.product-pricing.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-medium text-white/80 shadow-sm transition-all hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back to List
                        </a>
                        <form action="{{ route('admin.product-pricing.destroy', $price) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this price?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-2.5 text-sm font-medium text-red-300 shadow-sm transition-all hover:bg-red-500/20 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Product Details --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/50">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Product Details</h2>
                </div>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Title</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->product?->product_title ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Code</dt>
                        <dd class="text-sm font-mono font-medium text-slate-900 dark:text-white">{{ $price->product?->product_code ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">DDP Price</dt>
                        <dd class="text-sm font-semibold text-slate-900 dark:text-white">{{ config('app.currency_symbol') }}{{ number_format($price->product?->ddp_price ?? 0, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Category</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->product?->category?->name ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Client Details --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/50">
                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">User Details</h2>
                </div>
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Name</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->user?->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Email</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->user?->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Phone</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->user?->phone ?? 'N/A' }}</dd>
                    </div>
                    @if($price->assignedBy)
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Assigned By</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $price->assignedBy->name }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-sm text-slate-500 dark:text-neutral-400">Price Type</dt>
                        <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $typeLabel }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Price Summary --}}
            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                        <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Price Summary</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Product DDP Price</p>
                        <p class="mt-1.5 text-xl font-bold text-slate-900 dark:text-white">{{ config('app.currency_symbol') }}{{ number_format($price->product?->ddp_price ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">{{ $price->type === 'retail' ? 'Retail Price' : 'Assigned Price' }}</p>
                        <p class="mt-1.5 text-xl font-bold text-emerald-700 dark:text-emerald-400">{{ config('app.currency_symbol') }}{{ number_format($price->price, 2) }}</p>
                    </div>
                    @if($price->margin !== null)
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Margin</p>
                        <p class="mt-1.5 text-xl font-bold text-purple-700 dark:text-purple-400">{{ number_format($price->margin, 1) }}%</p>
                    </div>
                    @endif
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Discount / Markup vs DDP</p>
                        @php
                            $ddp = $price->product?->ddp_price ?? 0;
                            $diff = $ddp > 0 ? (($price->price - $ddp) / $ddp) * 100 : 0;
                        @endphp
                        <p class="mt-1.5 text-xl font-bold {{ $diff <= 0 ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $diff >= 0 ? '+' : '' }}{{ number_format($diff, 1) }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
