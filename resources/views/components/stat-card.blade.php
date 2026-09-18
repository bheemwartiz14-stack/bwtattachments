@if(isset($url) && $url)
<a href="{{ $url }}" wire:navigate class="block rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950">
@else
<div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
@endif
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-700 dark:text-neutral-400">{{ $label }}</p>
            <p class="mt-1 text-2xl font-semibold tracking-tight text-black dark:text-neutral-100">{{ $value }}</p>
        </div>
        @if(isset($icon))
            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconBg ?? 'bg-slate-100 dark:bg-neutral-900' }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
@if(isset($url) && $url)
</a>
@else
</div>
@endif
