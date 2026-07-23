@props([
    'name' => 'password',
    'id' => null,
    'label' => 'Password',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'hint' => '',
    'showGenerator' => true,
    'showToggle' => true,
    'showCopy' => true,
    'wrapperClass' => '',
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
@endphp

<div class="{{ $wrapperClass }}" data-password-wrapper>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <div class="relative">
        <input
            type="password"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder ?: 'Enter password' }}"
            data-password-input
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            {{ $attributes->merge(['class' => 'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:focus:ring-neutral-500 ' . ($hasError ? 'border-red-300 dark:border-red-400 text-red-900 dark:text-red-800 placeholder-red-300 dark:placeholder-red-400 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-300 bg-white dark:bg-white text-gray-900 dark:text-gray-900 placeholder-gray-400 dark:placeholder-gray-400 focus:border-neutral-500')]) }}
        />
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 space-x-1">
            @if($showGenerator)
                <button type="button" data-generate-password class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Generate password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </button>
            @endif
            @if($showToggle)
                <button type="button" data-toggle-password class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Toggle visibility">
                    <span data-show-icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                    <span data-hide-icon class="hidden"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></span>
                </button>
            @endif
            @if($showCopy)
                <button type="button" data-copy-password class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Copy password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                </button>
            @endif
        </div>
    </div>
    <span data-copied class="hidden text-xs text-emerald-600 dark:text-emerald-400 mt-1">Copied!</span>
    @if($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>
