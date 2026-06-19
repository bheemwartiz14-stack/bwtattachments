@props(['maxWidth' => 'lg', 'title' => ''])

@php
$maxWidths = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
];
@endphp

<div x-show="open" x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @@click.away="open = false"
        class="w-full {{ $maxWidths[$maxWidth] ?? 'sm:max-w-lg' }} overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
            <h3 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ $title }}</h3>
            <button @@click="open = false" class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-slate-100 dark:text-gray-500 dark:hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="px-6 py-4">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-white px-6 py-4 dark:border-slate-700 dark:bg-slate-800/50">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>