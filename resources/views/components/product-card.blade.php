<div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-200 group hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950">
    <div class="relative aspect-[3/2] overflow-hidden bg-slate-100 dark:bg-neutral-900">
        @if(isset($image) && $image)
            <img src="{{ $image }}" alt="{{ $title ?? '' }}" class="absolute inset-0 w-full h-full object-contain">
        @else
        <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-neutral-500">
            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        @endif
        <div class="absolute top-2 left-2 flex flex-wrap gap-1">
            @if(isset($category))
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $category }}</span>
            @endif
            @if(isset($subcategory))
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-neutral-800 dark:text-neutral-300">{{ $subcategory }}</span>
            @endif
        </div>
        @if(isset($connectionType))
            <div class="absolute top-2 right-2">
                <span class="inline-flex items-center rounded-full bg-slate-800/80 px-2.5 py-0.5 text-xs font-medium text-white">{{ $connectionType }}</span>
            </div>
        @endif
    </div>
    <div class="p-4">
        <p class="font-mono text-xs font-medium text-emerald-600 dark:text-emerald-400">{{ $code ?? 'BKT-001' }}</p>
        <h3 class="mt-1 line-clamp-2 text-sm font-semibold text-black dark:text-neutral-100">{{ $title ?? 'Product Name' }}</h3>
        <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-700 dark:text-neutral-400">
            @if(isset($weight))
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                    {{ $weight }}
                </span>
            @endif
            @if(isset($width))
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                    {{ $width }}
                </span>
            @endif
            @if(isset($machineClass))
                <span>{{ $machineClass }}</span>
            @endif
        </div>
        @if(isset($showPrice) && $showPrice)
            <div class="mt-3 border-t border-slate-100 pt-3 dark:border-neutral-800">
                <p class="text-lg font-bold text-black dark:text-neutral-100">{{ $price ?? '$0.00' }}</p>
            </div>
        @endif
        <div class="mt-3 flex gap-2">
            <a href="{{ $detailsUrl ?? '#' }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-[12px] border border-slate-200 bg-white px-4 py-1 text-center text-sm font-normal text-black transition-colors hover:bg-rose-50">Details</a>
            @if(isset($showQuoteBtn) && $showQuoteBtn)
                <button class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition-colors hover:bg-rose-100">+ Quote</button>
            @endif
        </div>
    </div>
</div>
