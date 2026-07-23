@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'options' => [],
    'value' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $selected = old($name, $value);
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $inputId }}"
        @if($multiple) multiple @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 ' . ($hasError ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 bg-white text-black focus:border-neutral-500')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $key => $option)
            @php
                $isSelected = $multiple ? in_array($key, (array)$selected) : $selected == $key;
            @endphp
            <option value="{{ $key }}" @if($isSelected) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
    @if($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>
