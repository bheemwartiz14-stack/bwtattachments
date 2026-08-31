<x-layouts.app>
    <x-slot:title>Orders - {{ $siteTitle }}</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Wholesaler Portal', 'url' => route('client.dashboard')],
        ['label' => 'Orders'],
    ]" />

    <div class="mb-6">
        <x-ui.hero title="Orders" icon="heroicon-o-clipboard-document-list">
            <x-slot:subtitle>Example order records for the order list layout.</x-slot:subtitle>
            <x-slot:actions>
                <a href="{{ route('client.order.create') }}" wire:navigate class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Create Order
                </a>
            </x-slot:actions>
        </x-ui.hero>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Order Number</th>
                        <th class="px-6 py-4 font-semibold">Reference</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @foreach($orders as $order)
                        <tr class="transition hover:bg-slate-50/70 dark:hover:bg-neutral-900/60">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-800 dark:text-neutral-100">{{ $order['number'] }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-neutral-300">{{ $order['reference'] }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-neutral-300">{{ $order['date'] }}</td>
                            <td class="px-6 py-4"><span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' => $order['status'] === 'Draft', 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $order['status'] === 'Sent', 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' => $order['status'] === 'Completed'])>{{ $order['status'] }}</span></td>
                            <td class="px-6 py-4 text-right font-semibold text-slate-800 dark:text-neutral-100">{{ $order['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
