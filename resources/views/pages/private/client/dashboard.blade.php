<x-layouts.app>
    <x-slot:title>Wholesaler Dashboard - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Wholesaler Portal']]" />

    @if (session('success'))
        <div
            class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        {{-- Welcome + Notifications row --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Welcome section --}}
            <div
                class="lg:col-span-2 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Welcome back,
                            {{ $user->name ?? 'Client' }}</h1>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Wholesaler</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        {{-- Statistics --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            <x-stat-card label="Products" :value="number_format($stats['total_products'])" :url="route('client.products.index')" iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card label="Total Orders" :value="$stats['total_orders']" :url="route('client.orders.index')" iconBg="bg-blue-50 dark:bg-blue-900/30"
                icon='<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>' />
            <x-stat-card label="Quotations" :value="$stats['quotations']" :url="route('client.quotations.index')" iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
        </div>

    </div>
</x-layouts.app>
