@props([
    'name' => 'product_pdf',
    'accept' => '.pdf,application/pdf',
    'label' => 'Product PDF',
    'hint' => 'PDF only &middot; Max 10 MB',
    'existingFile' => null,
    'existingUrl' => null,
    'existingSize' => null,
    'existingFileId' => null,
    'maxSize' => 10485760,
])

@php
    $tempInputName = $name . '_temp';
    $oldTokenJson = old($tempInputName);
    $uploadUrl = route('upload-temp');
    $csrfToken = csrf_token();
    $deleteBaseUrl = url('/media') . '/';
    $apiAccept = 'application/pdf';
@endphp

<div>
    @if($label)
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">{{ $label }}</h3>
    @endif
    <div class="js-pdf-upload"
        data-temp-name="{{ $tempInputName }}"
        data-max-size="{{ $maxSize }}"
        data-existing-file="{{ $existingFile ?? '' }}"
        data-existing-url="{{ $existingUrl ?? '' }}"
        data-existing-size="{{ $existingSize ?? '' }}"
        data-existing-file-id="{{ $existingFileId ?? '' }}"
        data-old-temp="{{ $oldTokenJson ?? '' }}">

        <input type="hidden" name="{{ $tempInputName }}" class="temp-input" value="{{ $oldTokenJson }}">
        <input type="file" accept="{{ $accept }}" class="file-input hidden">

        <div class="dropzone-area cursor-pointer rounded-xl border-2 border-dashed p-8 text-center transition-colors hover:border-slate-400 dark:hover:border-neutral-500 border-slate-300 dark:border-neutral-700">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-neutral-300">
                <span class="text-emerald-600 dark:text-emerald-400">Click to upload</span>
                or drag and drop
            </p>
            @if($hint)
                <p class="mt-1 text-xs text-slate-400 dark:text-neutral-500">{!! $hint !!}</p>
            @endif
        </div>

        <div class="uploading-area hidden mt-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm text-slate-600 dark:text-neutral-400">Uploading...</p>
                            <p class="upload-progress-text text-xs text-slate-500 dark:text-neutral-500">0%</p>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-neutral-700">
                            <div class="upload-progress-bar h-full rounded-full bg-emerald-500 transition-all duration-200" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="preview-area hidden mt-2">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/50">
                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="file-name truncate text-sm font-medium text-slate-900 dark:text-white"></p>
                        <p class="file-size-text text-xs text-slate-400 dark:text-neutral-500"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a class="download-btn hidden inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800" target="_blank">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Download
                        </a>
                        <button type="button" class="change-btn inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Change
                        </button>
                        <button type="button" class="remove-btn inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:bg-neutral-900 dark:text-red-400 dark:hover:bg-red-900/20">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <p class="error-msg mt-2 text-sm text-red-600 dark:text-red-400 hidden"></p>
    </div>
</div>

@push('scripts')
<script>
(function(){
    $('.js-pdf-upload').each(function(){
        var $el = $(this);
        var $tempInput = $el.find('.temp-input');
        var $fileInput = $el.find('.file-input');
        var $dropzone = $el.find('.dropzone-area');
        var $uploading = $el.find('.uploading-area');
        var $preview = $el.find('.preview-area');
        var $errorMsg = $el.find('.error-msg');
        var $progressText = $el.find('.upload-progress-text');
        var $progressBar = $el.find('.upload-progress-bar');
        var $fileName = $el.find('.file-name');
        var $fileSizeText = $el.find('.file-size-text');
        var $downloadBtn = $el.find('.download-btn');

        var maxSize = $el.data('max-size');
        var existingFile = $el.data('existing-file');
        var existingUrl = $el.data('existing-url');
        var existingSize = $el.data('existing-size');
        var existingFileId = $el.data('existing-file-id');
        var oldTempData = $el.data('old-temp');
        var uploadUrl = '{{ $uploadUrl }}';
        var csrfToken = '{{ $csrfToken }}';
        var deleteBaseUrl = '{{ $deleteBaseUrl }}';

        var file = null;
        var tempData = null;

        function formatSize(bytes) {
            if (!bytes) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function setError(msg) {
            if (msg) {
                $errorMsg.text(msg).removeClass('hidden');
            } else {
                $errorMsg.addClass('hidden');
            }
        }

        function showDropzone() {
            $dropzone.removeClass('hidden');
            $preview.addClass('hidden');
            $uploading.addClass('hidden');
        }

        function showPreview(name, size, showDownload, downloadHref) {
            console.log(downloadHref);
            $dropzone.addClass('hidden');
            $uploading.addClass('hidden');
            $preview.removeClass('hidden');
            $fileName.text(name);
            $fileSizeText.text(size);
            if (showDownload && downloadHref) {
                $downloadBtn.attr('href', downloadHref).removeClass('hidden');
            } else {
                $downloadBtn.addClass('hidden');
            }
        }

        function showUploading() {
            $dropzone.addClass('hidden');
            $preview.addClass('hidden');
            $uploading.removeClass('hidden');
            $progressText.text('0%');
            $progressBar.css('width', '0%');
        }

        function hasFile() {
            return !!(file || tempData || existingFile);
        }

        function render() {
            if (file && tempData) {
                showPreview(tempData.name, formatSize(tempData.size), false, null);
            } else if (existingFile) {
                showPreview(existingFile, formatSize(existingSize), true, existingUrl);
            } else {
                showDropzone();
            }
        }

        function initState() {
            if (oldTempData) {
                try {
                    var parsed = JSON.parse(oldTempData);
                    if (parsed && parsed.token) {
                        tempData = parsed;
                        file = true;
                        existingFile = null;
                        existingUrl = null;
                        existingSize = null;
                        existingFileId = null;
                    }
                } catch(e) {}
            }
            render();
        }

        function handleFileUpload(f) {
            if (!f) return;
            setError('');
            if (f.type !== '{{ $apiAccept }}') {
                setError('Only PDF files are allowed.');
                return;
            }
            if (f.size > maxSize) {
                setError('File size must be less than 10 MB.');
                return;
            }

            showUploading();

            var formData = new FormData();
            formData.append('file', f);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', uploadUrl);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var pct = Math.round((e.loaded / e.total) * 100);
                    $progressText.text(pct + '%');
                    $progressBar.css('width', pct + '%');
                }
            };
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    tempData = data;
                    file = f;
                    existingFile = null;
                    existingUrl = null;
                    existingSize = null;
                    existingFileId = null;
                    render();
                } else {
                    setError('Upload failed. Please try again.');
                    showDropzone();
                }
            };
            xhr.onerror = function() {
                setError('Upload failed. Please try again.');
                showDropzone();
            };
            xhr.send(formData);
        }

        function removeFile() {
            if (existingFileId) {
                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', deleteBaseUrl + existingFileId);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send();
            }
            file = null;
            existingFile = null;
            existingUrl = null;
            existingSize = null;
            existingFileId = null;
            tempData = null;
            $fileInput.val('');
            showDropzone();
            setError('');
        }

        $dropzone.on('click', function(){ $fileInput.click(); });
        $dropzone.on('dragover', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '#34d399').css('background-color', 'rgb(236 253 245)');
        });
        $dropzone.on('dragleave', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '').css('background-color', '');
        });
        $dropzone.on('drop', function(e){
            e.preventDefault();
            $dropzone.css('border-color', '').css('background-color', '');
            handleFileUpload(e.originalEvent.dataTransfer.files[0]);
        });

        $fileInput.on('change', function(){
            handleFileUpload(this.files[0]);
            this.value = '';
        });

        $preview.on('dragover', function(e){
            e.preventDefault();
            $preview.css('border-color', '#34d399').css('background-color', 'rgb(236 253 245)');
        });
        $preview.on('dragleave', function(e){
            e.preventDefault();
            $preview.css('border-color', '').css('background-color', '');
        });
        $preview.on('drop', function(e){
            e.preventDefault();
            $preview.css('border-color', '').css('background-color', '');
            handleFileUpload(e.originalEvent.dataTransfer.files[0]);
        });

        $el.find('.change-btn').on('click', function(){ $fileInput.click(); });
        $el.find('.remove-btn').on('click', removeFile);

        var form = $el.closest('form')[0];
        if (form) {
            $(form).on('submit', function(){
                if (tempData) {
                    $tempInput.val(JSON.stringify(tempData));
                } else {
                    $tempInput.val('');
                }
            });
        }

        initState();
    });
})();
</script>
@endpush
