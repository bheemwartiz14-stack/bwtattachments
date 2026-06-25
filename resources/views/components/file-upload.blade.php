@props(['multiple' => false, 'accept' => 'image/*', 'label' => 'Upload files', 'name' => 'file', 'required' => false])

@php
    $inputId = 'file-upload-' . uniqid();
@endphp

<div class="mt-1" data-upload-area>
    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-100 dark:border-neutral-700 rounded-xl transition-colors hover:border-neutral-400 dark:hover:border-neutral-500">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <p class="mt-2 text-sm text-gray-700 dark:text-neutral-400">
                <label for="{{ $inputId }}" class="cursor-pointer font-medium text-emerald-600 hover:text-emerald-700">Click to upload</label>
                or drag and drop
            </p>
            <p class="mt-1 text-xs text-gray-400 dark:text-neutral-500">PNG, JPG, PDF up to 10MB</p>
            <div class="file-list mt-4 text-left hidden"></div>
        </div>
    </div>
    <input type="file" id="{{ $inputId }}" name="{{ $multiple ? $name . '[]' : $name }}" class="hidden" accept="{{ $accept }}" @if($multiple) multiple @endif @if($required) required @endif>
</div>

@push('scripts')
    <script>
        $(document).on('change', '[data-upload-area] input[type="file"]', function() {
            var list = $(this).closest('[data-upload-area]').find('.file-list');
            list.empty().removeClass('hidden');
            $.each(this.files, function(i, file) {
                var size = (file.size / 1024).toFixed(1) + ' KB';
                var item = $('<div class="mt-1 flex items-center gap-2 rounded-lg bg-white dark:bg-neutral-900 px-3 py-2">' +
                    '<svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                    '<span class="flex-1 truncate text-sm text-gray-700 dark:text-neutral-400">' + file.name + '</span>' +
                    '<span class="text-xs text-gray-400">' + size + '</span>' +
                    '<button type="button" class="file-remove text-red-600 hover:text-red-700"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>' +
                    '</div>');
                list.append(item);
            });
        });
        $(document).on('click', '.file-remove', function() {
            $(this).closest('.mt-1').remove();
            var list = $(this).closest('.file-list');
            if (list.children().length === 0) list.addClass('hidden');
        });
    </script>
@endpushonce
