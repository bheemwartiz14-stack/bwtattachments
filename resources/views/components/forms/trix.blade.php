@props([
    'name',
    'label' => null,
    'value' => '',
    'required' => false,
    'error' => null,
])

@php
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
@endphp

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <input id="{{ $name }}_input"
           type="hidden"
           name="{{ $name }}"
           value="{{ old($name, $value) }}">

    <trix-editor input="{{ $name }}_input"
                  class="trix-content min-h-[200px] rounded-lg border bg-white dark:bg-neutral-900 @if($hasError) border-red-500 @endif"></trix-editor>

    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>
