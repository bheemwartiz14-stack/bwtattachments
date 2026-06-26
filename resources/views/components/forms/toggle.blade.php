@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'description' => '',
    'checked' => false,
    'disabled' => false,
    'error' => null,
    'wrapperClass' => '',
])

@php
    $inputId = $id ?? $name;
    $isChecked = old($name, $checked);
@endphp

<div class="{{ $wrapperClass }}">
    <label for="{{ $inputId }}" class="inline-flex items-center cursor-pointer">
        <input type="hidden" name="{{ $name }}" value="0">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="1"
            @if($isChecked) checked @endif
            @if($disabled) disabled @endif
            class="sr-only peer"
        >
        <div class="relative w-11 h-6 bg-red-400 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-neutral-300 dark:peer-focus:ring-neutral-600 rounded-full peer dark:bg-red-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-emerald-500 dark:peer-checked:bg-emerald-600"></div>
        <div class="ml-3">
            <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $label }}</span>
            @if($description)
                <p class="text-xs text-gray-500 dark:text-neutral-400">{{ $description }}</p>
            @endif
        </div>
    </label>
</div>
