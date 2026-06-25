@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'accept' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => '',
    'wrapperClass' => '',
    'currentFile' => null,
])

@php
    $inputId = $id ?? $name;
    $hasError = $error ? true : ($errors && $errors->has($name));
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $previewId = $inputId . '-preview';
@endphp

<div class="{{ $wrapperClass }}" data-file-input>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <div class="flex items-center gap-4">
        <div id="{{ $previewId }}"
            class="flex w-full items-center gap-2 rounded-lg border px-4 py-3 text-sm text-gray-500 dark:border-neutral-700 dark:text-neutral-400">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            @if($currentFile)
                <span class="truncate">{{ $currentFile }}</span>
            @else
                <span>No file selected</span>
            @endif
        </div>
        <button type="button" data-choose="{{ $inputId }}"
            class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Upload
        </button>
        <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="{{ $accept }}"
            @if($required) required @endif @if($disabled) disabled @endif class="hidden">
        <button type="button" data-remove="{{ $inputId }}"
            class="hidden rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Remove</button>
    </div>
    @if($hint && !$hasError)
        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">{{ $hint }}</p>
    @endif
    @if($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>

@push('scripts')
<script>
(function(){
    document.querySelectorAll('[data-file-input]').forEach(function(el){
        var input = el.querySelector('input[type="file"]');
        var preview = el.querySelector('[id$="-preview"]');
        var choose = el.querySelector('[data-choose]');
        var remove = el.querySelector('[data-remove]');
        if (!input || !preview) return;

        if (choose) {
            choose.addEventListener('click', function(){ input.click(); });
        }

        input.addEventListener('change', function(){
            var file = this.files[0];
            if (!file) return;
            preview.innerHTML =
                '<svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>' +
                '<span class="truncate">' + file.name + '</span>';
            if (remove) remove.classList.remove('hidden');
        });

        if (remove) {
            remove.addEventListener('click', function(){
                input.value = '';
                preview.innerHTML =
                    '<svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                    '<span>No file selected</span>';
                remove.classList.add('hidden');
            });
        }
    });
})();
</script>
@endpush
