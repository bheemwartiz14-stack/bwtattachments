@props([
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-3 ' . $class]) }}>
    {{ $slot }}
</div>
