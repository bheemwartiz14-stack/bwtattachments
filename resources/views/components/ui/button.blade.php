@props([
    'type' => 'button',
    'label' => 'Submit',
    'loading' => false,
    'disabled' => false,
    'variant' => 'primary',
])

@php
    $base = 'inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 focus:ring-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-700',
        'secondary' => 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 focus:ring-slate-400 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-700 dark:hover:bg-neutral-800',
    ];

    $variantClass = $variants[$variant] ?? $variants['primary'];
    $stateClass = ($loading || $disabled) ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer';
@endphp

<button
    type="{{ $type }}"
    @if($loading || $disabled) disabled @endif
    class="{{ $base }} {{ $variantClass }} {{ $stateClass }}"
    {{ $attributes }}
>
    <template x-if="false"></template>
    @if($loading)
        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    @else
        {{ $icon ?? '' }}
    @endif
    {{ $label ?? $slot }}
</button>
