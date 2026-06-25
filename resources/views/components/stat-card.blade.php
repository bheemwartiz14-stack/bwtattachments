<div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-700 dark:text-neutral-400">{{ $label }}</p>
            <p class="mt-1 text-2xl font-semibold tracking-tight text-black dark:text-neutral-100">{{ $value }}</p>
            @if(isset($trend))
                <p class="mt-2 flex items-center gap-1 text-xs font-medium {{ $trend === 'up' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                    @if($trend === 'up')
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    @else
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                    @endif
                    {{ $trendText ?? '' }}
                </p>
            @endif
        </div>
        @if(isset($icon))
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconBg ?? 'bg-slate-100 dark:bg-neutral-900' }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
