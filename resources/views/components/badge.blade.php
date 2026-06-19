@props(['variant' => 'primary', 'size' => 'md'])

@php
$base = 'inline-flex items-center font-medium rounded-full';
$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-xs',
    'lg' => 'px-3 py-1 text-sm',
];
$variants = [
    'primary' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300',
    'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300',
    'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
    'neutral' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
];
@endphp

<span {{ $attributes->merge(['class' => $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</span>