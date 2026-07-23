@props([
    'type' => 'button',
    'label' => 'Submit',
    'loading' => false,
    'disabled' => false,
    'variant' => 'primary',
    'block' => false,
    'icon' => '',
])

@php
    $base = 'cursor-pointer inline-flex items-center justify-center gap-2 rounded-xl px-5 text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 focus:ring-emerald-500 shadow-sm dark:bg-emerald-600 dark:hover:bg-emerald-700',
        'secondary' => 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 focus:ring-slate-400 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-700 dark:hover:bg-neutral-800',
        'submit' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30 dark:hover:bg-emerald-500 dark:focus:ring-offset-neutral-900',
        'danger' => 'text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20',
        'brand' => 'bg-bwtblue hover:bg-bwtblue2 text-white font-semibold rounded-lg px-8 py-3 shadow-sm hover:shadow-md',
        'ghost' => '',
    ];

    $sizes = [
        'sm' => 'h-9 py-2',
        'md' => 'h-10 py-2.5',
        'lg' => 'h-12 py-2.5',
    ];

    $size = $block ? 'lg' : 'md';

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $widthClass = $block ? 'w-full' : '';
    $computedClass = trim("{$base} {$variantClass} {$sizeClass} {$widthClass}");
@endphp

<button
    type="{{ $type }}"
    @if($loading || $disabled) disabled @endif
    {{ $attributes->merge(['class' => $computedClass]) }}
>
    @if($loading)
        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    @else
        {!! $icon ?? '' !!}
    @endif
    {{ $label ?? $slot }}
</button>
