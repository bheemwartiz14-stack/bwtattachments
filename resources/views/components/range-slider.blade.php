@props(['name' => '', 'value' => 0, 'min' => 0, 'max' => 100, 'step' => 1, 'unit' => '', 'label' => '', 'id' => null])

@php
    $inputId = $id ?? $name ?? 'range-' . uniqid();
    $currentVal = old($name, $value);
@endphp

<div class="space-y-2">
    @if($label)
        <div class="flex items-center justify-between">
            <label for="{{ $inputId }}" class="text-sm font-medium text-gray-700 dark:text-neutral-300">{{ $label }}</label>
            <span class="text-sm text-gray-500 dark:text-neutral-400" data-range-value="{{ $inputId }}">{{ $currentVal }} {{ $unit }}</span>
        </div>
    @endif
    <input type="range" name="{{ $name }}" id="{{ $inputId }}" value="{{ $currentVal }}" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}"
        class="w-full h-2 bg-gray-200 dark:bg-neutral-800 rounded-full appearance-none cursor-pointer accent-neutral-600"
        oninput="var target = this.closest('.space-y-2').querySelector('[data-range-value]'); if(target) target.textContent = this.value + ' {{ $unit }}';">
</div>
