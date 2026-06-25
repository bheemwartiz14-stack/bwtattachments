@props([
    'type' => 'info',
    'size' => 'sm',
    'label' => '',
])

@php
    $styles = [
        'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
        'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'neutral' => 'bg-gray-100 text-gray-800 dark:bg-neutral-900 dark:text-neutral-300',
        'primary' => 'bg-neutral-100 text-neutral-800 dark:bg-neutral-800 dark:text-neutral-300',
        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
        'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
        'active' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
        'inactive' => 'bg-gray-100 text-gray-800 dark:bg-neutral-900 dark:text-neutral-300',
        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'draft' => 'bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-neutral-300',
    ];
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];
    $style = $styles[$type] ?? $styles['neutral'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];
@endphp

<span class="inline-flex items-center rounded-full font-medium {{ $sizeClass }} {{ $style }}">
    {{ $label }}
</span>
