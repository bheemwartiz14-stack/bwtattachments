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
                  class="trix-content min-h-[200px] rounded-lg border border-gray-300 bg-white text-gray-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 @if($hasError) border-red-500 @endif"></trix-editor>

    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>

@once('trix-editor-styles')
<style>
    trix-editor [contenteditable] { color: #374151 !important; }
    .dark trix-editor { background-color: #171717 !important; border-color: #262626 !important; }
    .dark trix-editor,
    .dark trix-editor [contenteditable],
    .dark trix-editor trix-content { color: #e5e5e5 !important; }
    .dark trix-toolbar .trix-button-group { border-color: #262626; }
    .dark trix-toolbar .trix-button { color: #a3a3a3; border-color: #262626; }
    .dark trix-toolbar .trix-button:hover { color: #f5f5f5; background-color: #262626; }
    .dark trix-toolbar .trix-button.trix-active { color: #6366f1; background-color: #1e1b4b; }
</style>
@endonce
