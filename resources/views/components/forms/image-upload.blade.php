@props([
    'name' => 'image',
    'id' => null,
    'label' => 'Image',
    'accept' => 'image/*',
    'required' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
    'currentImage' => null,
    'currentImageUrl' => null,
    'deleteUrl' => null,
    'circle' => false,
    'size' => '192',
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $previewId = $inputId . '-preview';
    $placeholderId = $inputId . '-placeholder';
@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <div class="flex items-center gap-4">
        <div class="shrink-0">
            @if($currentImage || $currentImageUrl)
                <img src="{{ $currentImageUrl ?? Storage::url($currentImage) }}" alt="Current image"
                     class="{{ $circle ? 'rounded-full' : 'rounded-lg' }} object-cover"
                     style="width: {{ $size }}px; height: {{ $size }}px;">
            @else
                <div id="{{ $placeholderId }}" class="{{ $circle ? 'rounded-full' : 'rounded-lg' }} bg-gray-100 dark:bg-neutral-800 flex items-center justify-center text-gray-400"
                     style="width: {{ $size }}px; height: {{ $size }}px;">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
            <img id="{{ $previewId }}" class="{{ $circle ? 'rounded-full' : 'rounded-lg' }} object-cover hidden" style="width: {{ $size }}px; height: {{ $size }}px;">
        </div>
        <div>
            <label for="{{ $inputId }}" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-neutral-700 rounded-lg text-sm font-medium text-gray-700 dark:text-neutral-300 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Choose Image
            </label>
            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">PNG, JPG up to 2MB</p>
            @if($deleteUrl && $currentImageUrl)
                <a href="{{ $deleteUrl }}" class="mt-2 inline-flex items-center text-xs text-red-600 hover:text-red-700 dark:text-red-400" onclick="return confirm('Delete this image?')">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove
                </a>
            @endif
        </div>
    </div>
    <input
        type="file"
        name="{{ $name }}"
        id="{{ $inputId }}"
        accept="{{ $accept }}"
        @if($required) required @endif
        class="hidden"
        data-file-preview="#{{ $previewId }}"
    >
    @if($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>
