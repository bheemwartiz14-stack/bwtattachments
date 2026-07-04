<div class="relative" x-data="{ open: false }">
    <button type="button" @click="open = !open; if(open) { $nextTick(() => $refs.searchInput.focus()) }"
        class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-left text-sm shadow-sm transition-all hover:border-blue-300 hover:shadow-md focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-500">
        @if($selectedId)
            @php $sel = $customers->firstWhere('id', $selectedId); @endphp
            <span class="flex items-center gap-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700 dark:bg-blue-900/50 dark:text-blue-400">{{ strtoupper(substr($sel?->name ?? '?', 0, 1)) }}</span>
                <span class="font-medium text-slate-900 dark:text-white">{{ $sel?->name ?? 'Select customer...' }}</span>
            </span>
        @else
            <span class="text-slate-400 dark:text-neutral-500">Search for a customer...</span>
        @endif
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
    </button>

    @if($selectedId)
        @php $sel = $customers->firstWhere('id', $selectedId); @endphp
        <div class="mt-2 flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-900/50">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700 dark:bg-blue-900/50 dark:text-blue-400">
                {{ strtoupper(substr($sel?->name ?? '?', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $sel?->name }}</p>
                <p class="text-xs text-slate-500 dark:text-neutral-400">{{ $sel?->email }}{{ $sel?->phone ? ' · ' . $sel?->phone : '' }}</p>
            </div>
        </div>
    @endif

    <div x-show="open" @click.away="open = false" @keydown.escape.window="open = false"
        class="absolute left-0 right-0 z-50 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg ring-1 ring-black/5 dark:border-neutral-700 dark:bg-neutral-900">
        <div class="border-b border-slate-100 p-2 dark:border-neutral-800">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" x-ref="searchInput" wire:model.live.debounce.200ms="search"
                    placeholder="Type to search..."
                    class="block w-full rounded-lg border border-slate-200 bg-slate-50 pl-9 pr-3 py-2 text-sm text-slate-900 placeholder-slate-400 transition-colors focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500">
            </div>
        </div>
        <div class="max-h-60 overflow-y-auto p-1">
            @forelse($customers as $customer)
                <button type="button" wire:click="selectCustomer('{{ $customer->id }}')" @click="open = false"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm transition-colors hover:bg-slate-50 dark:hover:bg-neutral-800 {{ $selectedId === $customer->id ? 'bg-blue-50 dark:bg-neutral-800' : '' }}">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-100 to-slate-200 text-xs font-bold text-slate-600 dark:from-neutral-800 dark:to-neutral-700 dark:text-neutral-300">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white">{{ $customer->name }}</p>
                        <p class="text-xs text-slate-400 dark:text-neutral-500 truncate">{{ $customer->email }}{{ $customer->phone ? ' · ' . $customer->phone : '' }}</p>
                    </div>
                    @if($selectedId === $customer->id)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/50">
                            <svg class="h-3 w-3 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    @endif
                </button>
            @empty
                <div class="px-3 py-10 text-center">
                    <svg class="mx-auto h-8 w-8 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    <p class="mt-2 text-sm font-medium text-slate-400 dark:text-neutral-500">No customers found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
