<x-layouts.app>
    <x-slot:title>Dashboard - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Dashboard']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Dashboard</h1>
                <p class="text-sm text-gray-700 mt-1">Overview of your attachment portal</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </button>
                <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Add Product
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card
                label="Total Products"
                value="1,284"
                trend="up"
                trendText="12.5% from last month"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card
                label="Categories"
                value="24"
                trend="up"
                trendText="3 new this month"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' />
            <x-stat-card
                label="Active Clients"
                value="48"
                trend="up"
                trendText="+5 this month"
                iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            <x-stat-card
                label="Quotations"
                value="36"
                trend="up"
                trendText="8 this week"
                iconBg="bg-violet-50 dark:bg-violet-900/30"
                icon='<svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Recent Products</h2>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @foreach(['Bucket GP-12', 'Bucket HD-08', 'Ripper Tooth RT-4', 'Grapple GP-6', 'Quick Hitch QH-20'] as $i => $product)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900/50 transition-colors">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-900 text-gray-400 dark:text-neutral-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-black dark:text-neutral-100">{{ $product }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">BKT-00{{ $i + 1 }} &middot; Added {{ $i + 1 }}d ago</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-3 border-t border-slate-100 dark:border-neutral-800">
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all products</a>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Recent Quotations</h2>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @foreach([
                        ['id' => 'Q-2024-0042', 'client' => 'ABC Construction', 'total' => '$24,500', 'status' => 'completed'],
                        ['id' => 'Q-2024-0041', 'client' => 'BuildCorp Ltd', 'total' => '$12,800', 'status' => 'draft'],
                        ['id' => 'Q-2024-0040', 'client' => 'MineTech Inc', 'total' => '$45,200', 'status' => 'sent'],
                        ['id' => 'Q-2024-0039', 'client' => 'RoadWorks Co', 'total' => '$8,900', 'status' => 'completed'],
                        ['id' => 'Q-2024-0038', 'client' => 'EarthPro Ltd', 'total' => '$18,600', 'status' => 'draft'],
                    ] as $q)
                    <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900/50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-black dark:text-neutral-100">{{ $q['id'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">{{ $q['client'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-black dark:text-neutral-100">{{ $q['total'] }}</p>
                            @php
                                $badgeClasses = $q['status'] === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClasses }}">{{ ucfirst($q['status']) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-3 border-t border-slate-100 dark:border-neutral-800">
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all quotations</a>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Quick Actions</h2>
            </div>
            <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="#" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-neutral-800 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900 transition-colors group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <span class="text-sm font-medium text-black dark:text-neutral-100">Add Product</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-neutral-800 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900 transition-colors group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/50 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-black dark:text-neutral-100">Add Client</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-neutral-800 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900 transition-colors group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/30 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/50 transition-colors">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-black dark:text-neutral-100">New Quotation</span>
                </a>
                <a href="#" class="flex flex-col items-center gap-3 p-4 rounded-xl border border-slate-100 dark:border-neutral-800 hover:bg-rose-50 dark:bg-neutral-900/50 dark:hover:bg-neutral-900 transition-colors group">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/30 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/50 transition-colors">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <span class="text-sm font-medium text-black dark:text-neutral-100">Upload Media</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>