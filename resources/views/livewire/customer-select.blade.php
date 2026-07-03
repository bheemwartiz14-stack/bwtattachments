<div class="relative" x-data="{ open: false }">
    <button type="button" @click="open = !open; if(open) { $nextTick(() => $refs.searchInput.focus()) }"
        class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-left text-sm shadow-sm transition-all hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-500">
        @if($selectedId)
            @php $sel = $customers->firstWhere('id', $selectedId); @endphp
            <span class="font-medium text-slate-900 dark:text-white">{{ $sel?->name ?? 'Select customer...' }}</span>
        @else
            <span class="text-slate-400 dark:text-neutral-500">Search for a customer...</span>
        @endif
        <svg class="h-4 w-4 shrink-0 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
    </button>

    <div x-show="open" @click.away="open = false" @keydown.escape.window="open = false"
        class="absolute left-0 right-0 z-50 mt-1.5 rounded-xl border border-slate-200 bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-900">
        <div class="p-2">
            <input type="text" x-ref="searchInput" wire:model.live.debounce.200ms="search"
                placeholder="Type to search..."
                class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-900 placeholder-slate-400 transition-colors focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500">
        </div>
        <div class="max-h-60 overflow-y-auto">
            @forelse($customers as $customer)
                <button type="button" wire:click="selectCustomer('{{ $customer->id }}')" @click="open = false"
                    class="flex w-full items-center gap-3 px-3 py-2.5 text-left text-sm transition-colors hover:bg-slate-50 dark:hover:bg-neutral-800 {{ $selectedId === $customer->id ? 'bg-slate-50 dark:bg-neutral-800' : '' }}">
                    <div class="flex-1 min-w-0">

                        <p class="font-medium text-slate-900 dark:text-white {{ $selectedId === $customer->id ? '' : '' }}">{{ $customer->name }}</p>

                        <p class="text-xs text-slate-400 dark:text-neutral-500 truncate">{{ $customer->email }}{{ $customer->phone ? ' · ' . $customer->phone : '' }}</p>
                    </div>
                    @if($selectedId === $customer->id)

                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/50">
                            <svg class="h-3 w-3 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    @endif
                </button>
            @empty
                <div class="px-3 py-8 text-center text-sm text-slate-400 dark:text-neutral-500">No customers found</div>
            @endforelse
        </div>
    </div>
</div>
