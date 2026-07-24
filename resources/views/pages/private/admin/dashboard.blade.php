<x-layouts.app>
    <x-slot:title>Admin Dashboard - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin Poral']]" />

    <div class="space-y-6">
        <x-ui.hero title="Admin Dashboard">
           
        </x-ui.hero>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- STATS ROW --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Products" :value="number_format($stats['total_products'] ?? 0)" trend="up" :trendText="($stats['new_products'] ?? 0) . ' new this month'"
                iconBg="bg-emerald-100 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card label="Categories" :value="number_format($stats['total_categories'] ?? 0)" trend="up" :trendText="($stats['new_categories'] ?? 0) . ' new'"
                iconBg="bg-blue-100 dark:bg-blue-900/30"
                icon='<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' />
            <x-stat-card label="Active Clients" :value="number_format($stats['active_clients'] ?? 0)" trend="up" :trendText="'+' . ($stats['new_clients'] ?? 0) . ' this month'"
                iconBg="bg-amber-100 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            <x-stat-card label="Total Quotations" :value="number_format($stats['total_quotations'] ?? 0)" trend="up" :trendText="($stats['weekly_quotations'] ?? 0) . ' this week'"
                iconBg="bg-purple-100 dark:bg-purple-900/30"
                icon='<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
        </div>

        {{-- MANAGEMENT SECTIONS + QUICK STATS --}}
        <div class="">
            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Management Sections</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('admin.products.index') }}" wire:navigate class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-all hover:border-emerald-200 hover:bg-emerald-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-emerald-800 dark:hover:bg-emerald-900/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Products</p>
                            <p class="text-xs text-slate-500">{{ number_format($stats['total_products'] ?? 0) }} items</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"  wire:navigate class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-all hover:border-blue-200 hover:bg-blue-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-blue-800 dark:hover:bg-blue-900/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Categories</p>
                            <p class="text-xs text-slate-500">{{ number_format($stats['total_categories'] ?? 0) }} categories</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.subcategories.index') }}"  wire:navigate class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-all hover:border-indigo-200 hover:bg-indigo-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-indigo-800 dark:hover:bg-indigo-900/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Subcategories</p>
                            <p class="text-xs text-slate-500">{{ number_format($stats['total_subcategories'] ?? 0) }} total</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.connections.index') }}"  wire:navigate class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-all hover:border-teal-200 hover:bg-teal-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-teal-800 dark:hover:bg-teal-900/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100 dark:bg-teal-900/30">
                            <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Connections</p>
                            <p class="text-xs text-slate-500">Manage types</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.wholeseller.index') }}"   wire:navigate class="flex items-center gap-4 rounded-xl border border-slate-100 p-4 transition-all hover:border-amber-200 hover:bg-amber-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-amber-800 dark:hover:bg-amber-900/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">Wholesales</p>
                            <p class="text-xs text-slate-500">{{ number_format($stats['total_users'] ?? 0) }} accounts</p>
                        </div>
                    </a>
                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4 dark:border-neutral-800 dark:bg-neutral-900/30">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-neutral-900">
                            <svg class="w-6 h-6 text-slate-500 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500 dark:text-neutral-400">Companies</p>
                            <p class="text-xs text-slate-400">{{ number_format($stats['total_companies'] ?? 0) }} registered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RECENT PRODUCTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Recent Products</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">View All</a>
                </div>
                @if(isset($recentProducts) && $recentProducts->count())
                    <div class="space-y-2">
                        @foreach($recentProducts as $product)
                            <a href="{{ route('admin.products.show', $product) }}"  wire:navigate class="flex items-center justify-between rounded-xl p-3 transition-colors hover:bg-slate-50 dark:hover:bg-neutral-800/50">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-800">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-slate-900 dark:text-white">{{ $product->product_title ?? $product->product_description }}</p>
                                        <p class="text-xs text-slate-500">{{ $product->product_code }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 text-xs text-slate-400">{{ $product->created_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="py-8 text-center text-sm text-slate-400">No products yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
