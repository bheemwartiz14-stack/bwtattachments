<x-layouts.app>
    <x-slot:title>Admin Dashboard - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin'], ['label' => 'Dashboard']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Admin Dashboard</h1>
                <p class="text-sm text-gray-700 mt-1">Full system management</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Admin Access</span>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Products" :value="number_format($stats['total_products'] ?? 0)" trend="up" :trendText="($stats['new_products'] ?? 0) . ' new this month'"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card label="Categories" :value="number_format($stats['total_categories'] ?? 0)" trend="up" :trendText="($stats['new_categories'] ?? 0) . ' new'"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' />
            <x-stat-card label="Active Clients" :value="number_format($stats['active_clients'] ?? 0)" trend="up" :trendText="'+' . ($stats['new_clients'] ?? 0) . ' this month'"
                iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            <x-stat-card label="Total Quotations" :value="number_format($stats['total_quotations'] ?? 0)" trend="up" :trendText="($stats['weekly_quotations'] ?? 0) . ' this week'"
                iconBg="bg-violet-50 dark:bg-violet-900/30"
                icon='<svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Management Sections</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Products</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_products'] ?? 0) }} items</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Categories</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_categories'] ?? 0) }} categories</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/30 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/50 transition-colors">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Users</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_users'] ?? 0) }} accounts</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.quotations.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/30 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/50 transition-colors">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Quotations</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_quotations'] ?? 0) }} total</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.companies.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 dark:bg-cyan-900/30 group-hover:bg-cyan-100 dark:group-hover:bg-cyan-900/50 transition-colors">
                            <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Companies</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_companies'] ?? 0) }} registered</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.subcategories.index') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800 transition-colors group">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-900/30 group-hover:bg-rose-100 dark:group-hover:bg-rose-900/50 transition-colors">
                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Subcategories</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ number_format($stats['total_subcategories'] ?? 0) }} total</p>
                        </div>
                    </a>
                  
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Quick Stats</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-400">Products Published</span>
                            <span class="font-medium text-black dark:text-gray-100">{{ number_format($stats['published_products'] ?? 0) }} / {{ number_format($stats['total_products'] ?? 0) }}</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stats['total_products'] > 0 ? round(($stats['published_products'] / $stats['total_products']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-400">Active Users</span>
                            <span class="font-medium text-black dark:text-gray-100">{{ number_format($stats['active_users'] ?? 0) }} / {{ number_format($stats['total_users'] ?? 0) }}</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stats['total_users'] > 0 ? round(($stats['active_users'] / $stats['total_users']) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-400">Monthly Quotations</span>
                            <span class="font-medium text-black dark:text-gray-100">{{ number_format($stats['monthly_quotations'] ?? 0) }} / {{ number_format($stats['quotation_target'] ?? 50) }} target</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ ($stats['quotation_target'] ?? 50) > 0 ? round((($stats['monthly_quotations'] ?? 0) / ($stats['quotation_target'] ?? 50)) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Recent Products</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400">View All</a>
                </div>
                @if(isset($recentProducts) && $recentProducts->count())
                    <div class="space-y-3">
                        @foreach($recentProducts as $product)
                            <a href="{{ route('admin.products.show', $product) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-rose-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-gray-100">{{ $product->product_description ?? $product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->product_code }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $product->created_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">No products yet.</p>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Recent Quotations</h2>
                    <a href="{{ route('admin.quotations.index') }}" class="text-sm font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400">View All</a>
                </div>
                @if(isset($recentQuotations) && $recentQuotations->count())
                    <div class="space-y-3">
                        @foreach($recentQuotations as $quotation)
                            <a href="{{ route('admin.quotations.show', $quotation) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-rose-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 dark:bg-violet-900/30">
                                        <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-gray-100">{{ $quotation->quotation_number }}</p>
                                        <p class="text-xs text-gray-400">{{ $quotation->client->name ?? $quotation->client_name }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-black dark:text-gray-100">${{ number_format($quotation->total_amount ?? $quotation->total, 2) }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">No quotations yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
