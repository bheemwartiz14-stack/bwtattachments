@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => '',
    'prepend' => null,
    'append' => null,
    'class' => '',
    'wrapperClass' => '',
    'slugSource' => null,
    'generateUsername' => null,
    'selectAll' => false,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $slugAttr = $slugSource ? ' data-slug-source="' . $slugSource . '"' : '';
    $usernameAttr = $generateUsername ? 'data-generate-username="' . $generateUsername . '"' : '';
    $selectAttr = $selectAll ? ' data-select-all' : '';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <div class="relative">
        @if($prepend)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">{!! $prepend !!}</span>
            </div>
        @endif
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $slugAttr }}
            {{ $usernameAttr }}
            {{ $selectAttr }}
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:focus:ring-neutral-400 autofill:!bg-white autofill:!text-inherit dark:autofill:!bg-neutral-900 dark:autofill:!text-neutral-100 ' . ($hasError ? 'border-red-300 dark:border-red-600 text-red-900 dark:text-red-100 placeholder-red-300 dark:placeholder-red-400 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 placeholder-gray-400 dark:placeholder-neutral-500 focus:border-neutral-500') . ' ' . ($prepend ? 'pl-10' : '') . ' ' . ($append ? 'pr-10' : '') . ' ' . $class]) }}
        />
        @if($append)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">{!! $append !!}</span>
            </div>
        @endif
    </div>
    @if($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>
