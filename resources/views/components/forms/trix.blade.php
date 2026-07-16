@props(['name', 'label' => null, 'value' => '', 'required' => false, 'error' => null])

@php
    $hasError = $error ? true : $errors && $errors->has($name);
    $errorMsg = $error ?: ($errors ? $errors->first($name) : '');
    $editorId = $name . '_editor';
    $inputId = $name . '_input';
@endphp

<div class="space-y-1">
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input id="{{ $inputId }}" type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}">

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900 @if($hasError) dark:border-red-500 border-red-500 @endif">
        <div id="{{ $editorId }}" class="min-h-[200px]"></div>
    </div>

    @if ($hasError)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errorMsg }}</p>
    @endif
</div>

@once('quill-editor')
<style>
    .ql-toolbar {
        border: none !important;
        border-radius: 0.75rem 0.75rem 0 0;
        background-color: #f8fafc;
    }
    .ql-container {
        border: none !important;
        border-radius: 0 0 0.75rem 0.75rem;
        font-size: 0.875rem;
    }
    .ql-editor { min-height: 200px; white-space: pre-wrap; }
    .ql-editor p, .ql-editor br { margin-bottom: 0.5em; }
    .dark .ql-toolbar { background-color: #1a1a1a; }
    .dark .ql-toolbar .ql-stroke { stroke: #a3a3a3; }
    .dark .ql-toolbar .ql-fill { fill: #a3a3a3; }
    .dark .ql-toolbar .ql-picker-label { color: #a3a3a3; }
    .dark .ql-toolbar .ql-picker-options {
        background-color: #1a1a1a;
        border-color: #262626;
    }
    .dark .ql-toolbar .ql-picker-options .ql-picker-item:hover,
    .dark .ql-toolbar .ql-picker-label:hover { color: #6366f1; }
    .dark .ql-toolbar button:hover .ql-stroke,
    .dark .ql-toolbar button.ql-active .ql-stroke { stroke: #6366f1; }
    .dark .ql-toolbar button:hover .ql-fill,
    .dark .ql-toolbar button.ql-active .ql-fill { fill: #6366f1; }
    .dark .ql-editor { color: #e5e5e5; }
    .dark .ql-editor.ql-blank::before { color: #525252; }
</style>
<script>
    function initQuillEditor() {
        var editorEl = document.getElementById('{{ $editorId }}');
        var hiddenInput = document.getElementById('{{ $inputId }}');
        if (editorEl && hiddenInput && typeof Quill !== 'undefined') {
            if (editorEl.__quill) return;
            var quill = new Quill(editorEl, {
                theme: 'snow',
                placeholder: 'Enter {{ strtolower($label) }}...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['blockquote', 'code-block'],
                        ['link'],
                        ['clean']
                    ]
                }
            });
            editorEl.__quill = quill;
            if (hiddenInput.value) {
                quill.root.innerHTML = hiddenInput.value;
            }
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML;
            });
            var form = quill.root.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    hiddenInput.value = quill.root.innerHTML;
                });
            }
        }
    }
    document.addEventListener('DOMContentLoaded', initQuillEditor);
    document.addEventListener('livewire:navigated', initQuillEditor);
    document.addEventListener('livewire:init', initQuillEditor);
</script>
@endonce