<x-layouts.app>
    <x-slot:title>Clients - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => '#'], ['label' => 'Clients']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Wholesale Clients</h1>
                <p class="text-sm text-gray-700 mt-1">Manage your client accounts</p>
            </div>
            <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Client
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Clients" value="52" trend="up" trendText="+5 this month"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>' />
            <x-stat-card label="Active" value="48" trend="up" trendText="92% active rate"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>' />
            <x-stat-card label="Quotations This Month" value="32" trend="up" trendText="+8 vs last month"
                iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
            <x-stat-card label="Avg. Order Value" value="$8,450" trend="up" trendText="+12.5%"
                iconBg="bg-violet-50 dark:bg-violet-900/30"
                icon='<svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Quotations</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Since</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach([
                        ['name' => 'ABC Construction', 'contact' => 'John Smith', 'email' => 'john@abcconstruction.com', 'status' => 'active'],
                        ['name' => 'BuildCorp Ltd', 'contact' => 'Sarah Johnson', 'email' => 'sarah@buildcorp.com', 'status' => 'active'],
                        ['name' => 'MineTech Inc', 'contact' => 'Mike Chen', 'email' => 'mike@minetech.com', 'status' => 'active'],
                        ['name' => 'RoadWorks Co', 'contact' => 'Emily Davis', 'email' => 'emily@roadworks.com', 'status' => 'inactive'],
                        ['name' => 'EarthPro Ltd', 'contact' => 'David Wilson', 'email' => 'david@earthpro.com', 'status' => 'active'],
                        ['name' => 'HeavyEquip SA', 'contact' => 'Carlos Gomez', 'email' => 'carlos@heavyequip.com', 'status' => 'active'],
                        ['name' => 'Construction Pro', 'contact' => 'Anna Lee', 'email' => 'anna@constructionpro.com', 'status' => 'pending'],
                    ] as $client)
                    <tr class="hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-black dark:text-gray-100">{{ $client['name'] }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $client['contact'] }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $client['email'] }}</td>
                        <td class="px-6 py-4">
                            @if($client['status'] === 'active')
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                            @elseif($client['status'] === 'inactive')
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">Inactive</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ rand(2, 24) }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ rand(1, 24) }} months</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button title="Edit" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <button title="View Quotations" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                </button>
                                <button title="Delete" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>